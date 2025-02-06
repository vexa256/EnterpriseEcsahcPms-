<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrategicObjectivePerfomance extends Controller
{
    /**
     * Display a view for selecting a reporting year.
     */
    public function Ecsa_SO_selectYear(Request $request)
    {
        $years = DB::table('ecsahc_timelines')
            ->distinct()
            ->pluck('Year')
            ->sort()
            ->reverse()
            ->values();

        $Page = 'SO_Perfomance.SelectYear';
        return view('scrn', compact('Page', 'years'));
    }

    /**
     * Display a view for selecting a report from the chosen year.
     */
    public function Ecsa_SO_selectReport(Request $request)
    {
        $selectedYear = $request->input('year');
        $reports      = DB::table('ecsahc_timelines')
            ->where('Year', $selectedYear)
            ->get();

        $Page = 'SO_Perfomance.SelectReport';
        return view('scrn', compact('Page', 'reports', 'selectedYear'));
    }

    /**
     * Display the overall performance summary for each strategic objective
     * for the selected report. Only indicators that belong to the current user's
     * cluster (or are global) are shown if the user is not an admin.
     */
    public function Ecsa_SO_showPerformance(Request $request)
    {
        $selectedYear   = $request->input('year');
        $selectedReport = $request->input('report');

        $report = DB::table('ecsahc_timelines')
            ->where('ReportingID', $selectedReport)
            ->first();

        $allClusters     = DB::table('clusters')->get();
        $performanceData = $this->Ecsa_SO_getPerformanceData($selectedYear, $selectedReport, $allClusters);

        $Page = 'SO_Perfomance.Overview';
        return view('scrn', compact('Page', 'performanceData', 'report', 'selectedYear', 'selectedReport', 'allClusters'));
    }

    /**
     * Build aggregated performance data for each strategic objective.
     *
     * For each strategic objective:
     * - Retrieve its associated performance indicators.
     * - For each indicator, compute the baseline, target, score, and status.
     * - Decode the Responsible_Cluster array; if it contains "All clusters/projects",
     *   then override with all cluster IDs.
     * - If the current user is not Admin, only include the indicator if the user's
     *   cluster is among the responsible clusters.
     * - Also, determine which clusters have not reported.
     */
    private function Ecsa_SO_getPerformanceData($year, $reportId, $allClusters)
    {
        $objectives      = DB::table('strategic_objectives')->get();
        $performanceData = [];
        $allClusterIDs   = $allClusters->pluck('ClusterID')->toArray();

        // Determine current user information.
        $currentUser = Auth::user();
        $isAdmin     = ($currentUser->AccountRole === 'Admin');
        $userCluster = $currentUser->ClusterID;

        foreach ($objectives as $objective) {
            $indicators = DB::table('performance_indicators')
                ->where('SO_ID', $objective->StrategicObjectiveID)
                ->get();

            $objectiveSummary = [
                'name'           => $objective->SO_Name,
                'description'    => $objective->Description,
                'indicators'     => [],
                'fullyReported'  => true,
                'allTargetsMet'  => true,
                'highPerforming' => [],
                'missingReports' => [],
            ];

            foreach ($indicators as $indicator) {
                $baseline = $this->Ecsa_SO_getBaselineForYear($indicator->id, $year);
                $target   = $this->Ecsa_SO_getTargetForYear($indicator->id, $year);
                $score    = $this->Ecsa_SO_calculateScore($indicator, $reportId);
                $status   = $this->Ecsa_SO_calculateStatus($score, $baseline, $target, $indicator);

                $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
                if (! is_array($responsibleClusters)) {
                    $responsibleClusters = [];
                }
                // If the indicator is global, use all cluster IDs.
                if (in_array("All clusters/projects", $responsibleClusters)) {
                    $responsibleClusters = $allClusterIDs;
                }

                // For non-admin users, only include this indicator if the user's cluster is responsible.
                if (! $isAdmin && ! in_array($userCluster, $responsibleClusters)) {
                    continue;
                }

                $reportedClusters = $this->Ecsa_SO_getReportedClusterIDs($indicator->id, $reportId);
                $missingClusters  = array_values(array_diff($responsibleClusters, $reportedClusters));

                if (! empty($missingClusters)) {
                    $objectiveSummary['fullyReported']    = false;
                    $objectiveSummary['missingReports'][] = [
                        'indicator'       => $indicator->Indicator_Name,
                        'missingClusters' => $missingClusters,
                    ];
                }

                if ($status === 'met') {
                    $objectiveSummary['highPerforming'][] = $indicator->Indicator_Name;
                }
                if ($status !== 'met') {
                    $objectiveSummary['allTargetsMet'] = false;
                }

                $objectiveSummary['indicators'][] = [
                    'name'                => $indicator->Indicator_Name,
                    'baseline'            => $baseline,
                    'target'              => $target,
                    'score'               => $score,
                    'status'              => $status,
                    'responsibleClusters' => $responsibleClusters,
                    'reportedClusters'    => $reportedClusters,
                    'missingClusters'     => $missingClusters,
                    'responseType'        => $indicator->ResponseType,
                ];
            }

            // Only include objectives that have at least one applicable indicator.
            if (! empty($objectiveSummary['indicators'])) {
                $performanceData[$objective->StrategicObjectiveID] = $objectiveSummary;
            }
        }

        return $performanceData;
    }

    /**
     * Retrieve baseline for an indicator (always stored in Baseline_2023_2024).
     */
    private function Ecsa_SO_getBaselineForYear($indicatorId, $year)
    {
        return DB::table('performance_indicators')
            ->where('id', $indicatorId)
            ->value('Baseline_2023_2024');
    }

    /**
     * Retrieve target for an indicator based on the selected year.
     */
    private function Ecsa_SO_getTargetForYear($indicatorId, $year)
    {
        $indicator = DB::table('performance_indicators')
            ->where('id', $indicatorId)
            ->first();

        if ($year == 2024) {
            return $indicator->Target_Year1;
        }
        if ($year == 2025) {
            return $indicator->Target_Year2;
        }
        if ($year == 2026) {
            return $indicator->Target_Year3;
        }
        return null;
    }

    /**
     * Calculate the score for an indicator.
     * For numeric indicators, sum all responses.
     * For Yes/No or Boolean indicators, count affirmative responses and return a percentage.
     */
    private function Ecsa_SO_calculateScore($indicator, $reportId)
    {
        $responses = DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicator->id)
            ->where('ReportingID', $reportId)
            ->pluck('Response')
            ->toArray();

        switch ($indicator->ResponseType) {
            case 'Number':
                return array_sum($responses);
            case 'Yes/No':
            case 'Boolean':
                $affirmativeCount = count(array_filter($responses, function ($response) {
                    return in_array(strtolower($response), ['yes', 'true', '1']);
                }));
                $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
                if (! is_array($responsibleClusters)) {
                    $responsibleClusters = [];
                }
                $expectedCount = count($responsibleClusters);
                return ($expectedCount > 0) ? ($affirmativeCount / $expectedCount * 100) : 0;
            default:
                return null;
        }
    }

    /**
     * Determine the indicator's performance status.
     */
    private function Ecsa_SO_calculateStatus($score, $baseline, $target, $indicator)
    {
        if ($score === null || $baseline === null || $target === null) {
            return 'not performing';
        }

        switch ($indicator->ResponseType) {
            case 'Number':
                $range    = $target - $baseline;
                $progress = $score - $baseline;
                if ($progress >= $range) {
                    return 'met';
                } elseif ($progress > 0) {
                    return 'progressing';
                } else {
                    return 'not performing';
                }
            case 'Yes/No':
            case 'Boolean':
                if ($score >= 100) {
                    return 'met';
                } elseif ($score > $baseline) {
                    return 'progressing';
                } else {
                    return 'not performing';
                }
            default:
                return 'N/A';
        }
    }

    /**
     * Retrieve reported cluster IDs for an indicator.
     */
    private function Ecsa_SO_getReportedClusterIDs($indicatorId, $reportId)
    {
        return DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicatorId)
            ->where('ReportingID', $reportId)
            ->pluck('ClusterID')
            ->toArray();
    }

    /**
     * Calculate an overall strategic objective status.
     */
    private function Ecsa_SO_calculateObjectiveStatus($metCount, $totalIndicators)
    {
        $percentage = ($totalIndicators > 0) ? ($metCount / $totalIndicators) * 100 : 0;
        if ($percentage >= 75) {
            return 'met';
        } elseif ($percentage >= 50) {
            return 'progressing';
        } else {
            return 'not performing';
        }
    }

    /**
     * Export the strategic objective performance data as a CSV file.
     */
    public function Ecsa_SO_exportCsv(Request $request)
    {
        $selectedYear   = $request->input('year');
        $selectedReport = $request->input('report');

        $allClusters     = DB::table('clusters')->get();
        $performanceData = $this->Ecsa_SO_getPerformanceData($selectedYear, $selectedReport, $allClusters);

        $filename = "strategic_objective_performance_{$selectedYear}.csv";
        $headers  = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = ['Strategic Objective', 'Indicator', 'Baseline', 'Target', 'Score', 'Status', 'Missing Clusters'];

        $callback = function () use ($performanceData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($performanceData as $objective) {
                foreach ($objective['indicators'] as $indicator) {
                    fputcsv($file, [
                        $objective['name'],
                        $indicator['name'],
                        $indicator['baseline'] ?? 'N/A',
                        $indicator['target'] ?? 'N/A',
                        $indicator['score'] ?? 'N/A',
                        $indicator['status'] ?? 'N/A',
                        empty($indicator['missingClusters']) ? 'None' : implode(', ', $indicator['missingClusters']),
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}