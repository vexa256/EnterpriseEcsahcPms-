<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcsaIndicatorPerformanceController extends Controller
{
    public function selectCluster()
    {
        // If the current user is not an admin, show only the user's attached cluster.
        if (Auth::user()->AccountRole !== 'Admin') {
            $clusters = DB::table('clusters')
                ->where('ClusterID', Auth::user()->ClusterID)
                ->get();
        } else {
            $clusters = DB::table('clusters')->get();
        }
        $Page = 'EcsaAnalytics.SelectCluster';
        return view('scrn', compact('Page', 'clusters'));
    }

    public function selectYear(Request $request)
    {
        $selectedCluster = $request->input('cluster', 'All clusters');
        $clusters        = DB::table('clusters')->get();
        $years           = DB::table('ecsahc_timelines')
            ->distinct()
            ->pluck('Year')
            ->sort()
            ->reverse()
            ->values();

        $Page = 'EcsaAnalytics.SelectYear';
        return view('scrn', compact('Page', 'years', 'clusters', 'selectedCluster'));
    }

    public function selectReport(Request $request)
    {
        $selectedCluster = $request->input('cluster');
        $selectedYear    = $request->input('year');
        $reports         = DB::table('ecsahc_timelines')
            ->where('Year', $selectedYear)
            ->get();

        // Retrieve clusters so the view can properly display cluster details.
        $clusters = DB::table('clusters')->get();

        $Page = 'EcsaAnalytics.SelectReport';
        return view('scrn', compact('Page', 'reports', 'clusters', 'selectedCluster', 'selectedYear'));
    }

    public function showPerformance(Request $request)
    {
        $selectedCluster = $request->input('cluster');
        $selectedYear    = $request->input('year');
        $selectedReport  = $request->input('report');

        $clusters = DB::table('clusters')->get();
        $report   = DB::table('ecsahc_timelines')
            ->where('ReportingID', $selectedReport)
            ->first();

        $performanceData = $this->getPerformanceData($selectedCluster, $selectedYear, $selectedReport);

        $Page = 'EcsaAnalytics.IndicatorPerformance';
        return view('scrn', compact(
            'Page',
            'performanceData',
            'clusters',
            'selectedCluster',
            'selectedYear',
            'selectedReport',
            'report'
        ));
    }

    private function getPerformanceData($selectedCluster, $selectedYear, $selectedReport)
    {
        $objectives      = DB::table('strategic_objectives')->limit(100)->get();
        $performanceData = [];

        // Get all clusters' IDs for later use.
        $allClusterIDs = DB::table('clusters')->pluck('ClusterID')->toArray();

        foreach ($objectives as $objective) {
            $indicators = DB::table('performance_indicators')
                ->where('SO_ID', $objective->StrategicObjectiveID)
                ->limit(100)
                ->get();

            $objectiveData = [
                'name'        => $objective->SO_Name,
                'description' => $objective->Description,
                'indicators'  => [],
                'status'      => 'not performing',
            ];

            $metCount        = 0;
            $totalIndicators = count($indicators);

            foreach ($indicators as $indicator) {
                // Decode the responsible clusters; if not an array, default to an empty array.
                $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
                if (! is_array($responsibleClusters)) {
                    $responsibleClusters = [];
                }
                // Determine if the indicator is global.
                $isGlobal = in_array("All clusters/projects", $responsibleClusters);
                // Only include this indicator if either it is global or the selected cluster is among the responsible ones.
                if (! $isGlobal && $selectedCluster !== 'All clusters' && ! in_array($selectedCluster, $responsibleClusters)) {
                    continue;
                }

                $baseline         = $this->getBaselineForYear($indicator->id, $selectedYear);
                $target           = $this->getTargetForYear($indicator->id, $selectedYear);
                $score            = $this->calculateScore($indicator, $selectedCluster, $selectedReport);
                $status           = $this->calculateStatus($score, $baseline, $target, $indicator);
                $clusterResponses = $this->getClusterResponses($indicator->id, $selectedReport);

                $objectiveData['indicators'][] = [
                    'name'                => $indicator->Indicator_Name,
                    'baseline'            => $baseline,
                    'target'              => $target,
                    'score'               => $score,
                    'status'              => $status,
                    'responseType'        => $indicator->ResponseType,
                    'isGlobal'            => $isGlobal,
                    'responsibleClusters' => $responsibleClusters,
                    'clusterResponses'    => $clusterResponses,
                ];

                if ($status === 'met') {
                    $metCount++;
                }
            }

            // Sort the indicators so that global ones appear first.
            usort($objectiveData['indicators'], function ($a, $b) {
                return $b['isGlobal'] <=> $a['isGlobal'];
            });

            $objectiveData['status']                           = $this->calculateObjectiveStatus($metCount, $totalIndicators);
            $performanceData[$objective->StrategicObjectiveID] = $objectiveData;
        }

        return $performanceData;
    }

    private function getBaselineForYear($indicatorId, $year)
    {
        // For all years, the baseline is stored in Baseline_2023_2024.
        return DB::table('performance_indicators')
            ->where('id', $indicatorId)
            ->value('Baseline_2023_2024');
    }

    private function getTargetForYear($indicatorId, $year)
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
     * Calculate the raw score based on responses.
     * For "Number" indicators, we sum the responses.
     * For "Yes/No" or "Boolean", we compute a percentage of affirmative responses.
     */
    private function calculateScore($indicator, $clusterId, $reportId)
    {
        // Build the query for responses.
        $query = DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicator->id)
            ->where('ReportingID', $reportId);

        $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
        if (! is_array($responsibleClusters)) {
            $responsibleClusters = [];
        }

        // If the Responsible_Cluster does not include "All clusters/projects"
        // and a specific cluster is selected, filter by that cluster.
        if ($clusterId !== 'All clusters' && ! in_array("All clusters/projects", $responsibleClusters)) {
            $query->where('ClusterID', $clusterId);
        }

        $responses = $query->pluck('Response')->toArray();

        switch ($indicator->ResponseType) {
            case 'Number':
                return array_sum($responses);
            case 'Yes/No':
            case 'Boolean':
                $affirmativeCount = count(array_filter($responses, function ($response) {
                    return in_array(strtolower($response), ['yes', 'true', '1']);
                }));
                $expectedCount = count($responsibleClusters);
                return ($expectedCount > 0) ? ($affirmativeCount / $expectedCount * 100) : 0;
            default:
                return null;
        }
    }

    /**
     * Calculate the performance status based on the computed score.
     * For numeric indicators, we compute the percentage progress based on the baseline and target.
     * For Boolean/Yes-No indicators, the score is already a percentage.
     * Then we apply the following thresholds:
     * - Not Performing: < 10%
     * - In Progress: 10% to < 50%
     * - On Track: 50% to < 90%
     * - Met: ≥ 90%
     */
    private function calculateStatus($score, $baseline, $target, $indicator)
    {
        // If any key value is missing, return "not performing".
        if ($score === null || $baseline === null || $target === null) {
            return 'not performing';
        }

        // For numeric indicators, compute the percentage progress.
        if ($indicator->ResponseType === 'Number') {
            $range = $target - $baseline;
            if ($range == 0) {
                return ($score >= $target) ? 'met' : 'not performing';
            }
            $percentage = (($score - $baseline) / $range) * 100;
        }
        // For Boolean/Yes-No indicators, the score is already a percentage.
        elseif (in_array($indicator->ResponseType, ['Yes/No', 'Boolean'])) {
            $percentage = $score;
        } else {
            return 'N/A';
        }

        if ($percentage < 10) {
            return 'not performing';
        } elseif ($percentage < 50) {
            return 'in progress';
        } elseif ($percentage < 90) {
            return 'on track';
        } else {
            return 'met';
        }
    }

    private function getClusterResponses($indicatorId, $reportId)
    {
        // Convert each stdClass object to an array so that Blade can use array notation.
        return DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicatorId)
            ->where('ReportingID', $reportId)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->keyBy('ClusterID')
            ->toArray();
    }

    /**
     * Calculate overall objective status based on the percentage of indicators that are "met"
     * using the same thresholds:
     * - Not Performing: < 10%
     * - In Progress: 10% to < 50%
     * - On Track: 50% to < 90%
     * - Met: ≥ 90%
     */
    private function calculateObjectiveStatus($metCount, $totalIndicators)
    {
        $percentage = ($totalIndicators > 0) ? ($metCount / $totalIndicators * 100) : 0;

        if ($percentage < 10) {
            return 'not performing';
        } elseif ($percentage < 50) {
            return 'in progress';
        } elseif ($percentage < 90) {
            return 'on track';
        } else {
            return 'met';
        }
    }

    public function exportCsv(Request $request)
    {
        $selectedCluster = $request->input('cluster');
        $selectedYear    = $request->input('year');
        $selectedReport  = $request->input('report');

        $performanceData = $this->getPerformanceData($selectedCluster, $selectedYear, $selectedReport);

        $filename = "performance_report_{$selectedCluster}_{$selectedYear}.csv";
        $headers  = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = ['Objective', 'Indicator', 'Baseline', 'Target', 'Score', 'Status'];

        $callback = function () use ($performanceData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($performanceData as $objective) {
                foreach ($objective['indicators'] as $indicator) {
                    fputcsv($file, [
                        $objective['name'],
                        $indicator['name'],
                        $indicator['baseline'],
                        $indicator['target'],
                        $indicator['score'],
                        $indicator['status'],
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
