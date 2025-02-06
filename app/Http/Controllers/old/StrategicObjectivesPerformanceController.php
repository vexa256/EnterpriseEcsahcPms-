<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StrategicObjectivesPerformanceController extends Controller
{
    private function validateAndRedirect($data, $rules)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        return null;
    }

    public function selectYear()
    {
        $years = DB::table('ecsahc_timelines')->distinct()->orderBy('Year', 'desc')->pluck('Year');
        return view('scrn', [
            'Desc'  => 'Select a Year for Reporting',
            'Page'  => 'EcsaReporting.EcsaSelectYear',
            'years' => $years,
        ]);
    }

    public function selectReport(Request $request)
    {
        $validationResult = $this->validateAndRedirect($request->all(), [
            'year' => 'required|integer',
        ]);
        if ($validationResult) {
            return $validationResult;
        }

        $year      = $request->input('year');
        $timelines = DB::table('ecsahc_timelines')
            ->where('Year', $year)
            ->orderBy('ClosingDate', 'desc')
            ->get();

        if ($timelines->isEmpty()) {
            return redirect()->route('select-year')->with('error', 'No reports found for the selected year.');
        }

        return view('scrn', [
            'Desc'      => "Select a Report for Year $year",
            'Page'      => 'EcsaReporting.EcsaSelectTimeline',
            'timelines' => $timelines,
            'year'      => $year,
        ]);
    }

    public function performanceOverview(Request $request)
    {
        $validationResult = $this->validateAndRedirect($request->all(), [
            'reportingId' => 'required|string',
        ]);
        if ($validationResult) {
            return $validationResult;
        }

        $reportingId = $request->input('reportingId');
        $timeline    = DB::table('ecsahc_timelines')->where('ReportingID', $reportingId)->first();

        if (! $timeline) {
            return redirect()->route('select-year')->with('error', 'Invalid report selected.');
        }

        $indicators = DB::table('performance_indicators')->get();
        $report     = $this->generatePerformanceReport($indicators, $reportingId, $timeline->Year);

        \Log::debug("Generated performance report: " . json_encode($report));

        return view('scrn', [
            'Desc'        => "Performance Overview for {$timeline->ReportName}",
            'Page'        => 'EcsaAnalytics.EcsaSummary',
            'report'      => $report,
            'timeline'    => $timeline,
            'year'        => $timeline->Year,
            'reportingId' => $reportingId,
        ]);
    }

    private function generatePerformanceReport($indicators, $reportingId, $year)
    {
        $report = [];

        foreach ($indicators as $indicator) {
            \Log::debug("Processing indicator: " . $indicator->id);

            $responses = DB::table('cluster_performance_mappings')
                ->where('ReportingID', $reportingId)
                ->where('IndicatorID', $indicator->id)
                ->get();

            \Log::debug("Found " . $responses->count() . " responses for indicator " . $indicator->id);

            $score  = $this->calculateScore($indicator, $responses);
            $status = $this->determineStatus($score, $indicator, $year);

            \Log::debug("Calculated score: " . json_encode($score) . ", Status: " . $status);

            $report[$indicator->SO_ID][] = [
                'indicator' => $indicator,
                'score'     => $score,
                'status'    => $status,
            ];
        }

        $strategicObjectives = DB::table('strategic_objectives')->get();
        foreach ($strategicObjectives as $so) {
            if (isset($report[$so->StrategicObjectiveID])) {
                $soStatus                                                 = $this->determineStrategicObjectiveStatus($report[$so->StrategicObjectiveID]);
                $report[$so->StrategicObjectiveID]['overall_status']      = $soStatus;
                $report[$so->StrategicObjectiveID]['strategic_objective'] = $so;
            }
        }

        return $report;
    }

    private function calculateScore($indicator, $responses)
    {
        \Log::debug("Calculating score for indicator: " . $indicator->id);
        \Log::debug("Number of responses: " . $responses->count());

        if ($indicator->ResponseType === 'Number') {
            $sum = $responses->sum(function ($response) {
                \Log::debug("Response value: " . $response->Response);
                return (float) $response->Response;
            });
            \Log::debug("Calculated sum: " . $sum);
            return $sum;
        } elseif (in_array($indicator->ResponseType, ['Boolean', 'Yes/No'])) {
            $affirmativeCount = $responses->filter(function ($response) {
                return in_array(strtolower($response->Response), ['yes', 'true', '1']);
            })->count();
            $expectedCount = count(json_decode($indicator->Responsible_Cluster));
            \Log::debug("Affirmative count: " . $affirmativeCount . ", Expected count: " . $expectedCount);
            return [$affirmativeCount, $expectedCount];
        }
        \Log::debug("Returning null score");
        return null;
    }

    private function determineStatus($score, $indicator, $year)
    {
        if ($score === null) {
            return 'N/A';
        }

        $targetColumn = 'Target_Year' . ($year - 2023);
        $target       = $indicator->$targetColumn;
        $baseline     = $indicator->Baseline_2023_2024;

        if (is_array($score)) {
            list($affirmativeCount, $expectedCount) = $score;
            if ($affirmativeCount >= $expectedCount) {
                return 'met';
            }
            if ($affirmativeCount >= $baseline) {
                return 'progressing';
            }
            return 'not performing';
        }

        if ($score >= $target) {
            return 'met';
        }
        if ($score >= $baseline) {
            return 'progressing';
        }
        return 'not performing';
    }

    private function determineStrategicObjectiveStatus($indicators)
    {
        $statuses = array_column($indicators, 'status');
        $statuses = array_filter($statuses, function ($status) {
            return $status !== 'N/A';
        });

        if (empty($statuses)) {
            return 'N/A';
        }
        if (in_array('not performing', $statuses)) {
            return 'Not Performing';
        }
        if (count(array_unique($statuses)) === 1 && $statuses[0] === 'met') {
            return 'Targets Met';
        }
        return 'Progressing';
    }

    public function exportCsv(Request $request)
    {
        $validationResult = $this->validateAndRedirect($request->all(), [
            'reportingId' => 'required|string',
        ]);
        if ($validationResult) {
            return $validationResult;
        }

        $reportingId = $request->input('reportingId');
        $timeline    = DB::table('ecsahc_timelines')->where('ReportingID', $reportingId)->first();

        if (! $timeline) {
            return redirect()->route('select-year')->with('error', 'Invalid report selected.');
        }

        $indicators = DB::table('performance_indicators')->get();
        $report     = $this->generatePerformanceReport($indicators, $reportingId, $timeline->Year);

        $filename = "performance_report_{$reportingId}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = ['Strategic Objective', 'Indicator', 'Score', 'Status'];

        $callback = function () use ($report, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($report as $soId => $data) {
                $soName        = $data['strategic_objective']->SO_Name ?? 'Unknown';
                $overallStatus = $data['overall_status'] ?? 'N/A';
                fputcsv($file, [$soName, 'Overall Status', '', $overallStatus]);

                foreach ($data as $item) {
                    if (is_array($item) && isset($item['indicator'])) {
                        $score = is_array($item['score']) ? implode('/', $item['score']) : $item['score'];
                        fputcsv($file, [
                            $soName,
                            $item['indicator']->Indicator_Name,
                            $score,
                            $item['status'],
                        ]);
                    }
                }
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}