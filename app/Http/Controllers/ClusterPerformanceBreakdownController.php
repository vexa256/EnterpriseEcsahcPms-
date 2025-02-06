<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusterPerformanceBreakdownController extends Controller
{
    /**
     * Display a view for selecting a reporting year.
     */
    public function Ecsa_CP_selectYear(Request $request)
    {
        $years = DB::table('ecsahc_timelines')
            ->distinct()
            ->pluck('Year')
            ->sort()
            ->reverse()
            ->values();

        $Page = 'Cluster_Perfomance.SelectYear';
        return view('scrn', compact('Page', 'years'));
    }

    /**
     * Display a view for selecting a report from the chosen year.
     */
    public function Ecsa_CP_selectReport(Request $request)
    {
        $selectedYear = $request->input('year');
        $reports      = DB::table('ecsahc_timelines')
            ->where('Year', $selectedYear)
            ->get();

        $Page = 'Cluster_Perfomance.SelectReport';
        return view('scrn', compact('Page', 'reports', 'selectedYear'));
    }

    /**
     * Display the Cluster Performance Breakdown report.
     * This report lists each cluster with its overall performance:
     * - Percentage of applicable indicators that are met, progressing, or not performing.
     * - Details on which indicators are missing reporting.
     */
    public function Ecsa_CP_showPerformance(Request $request)
    {
        $selectedYear   = $request->input('year');
        $selectedReport = $request->input('report');

        // Get the selected report details.
        $report = DB::table('ecsahc_timelines')
            ->where('ReportingID', $selectedReport)
            ->first();

        // Get all clusters.
        $clusters = DB::table('clusters')->get();

        // Generate performance breakdown for each cluster.
        $performanceData = $this->Ecsa_CP_getClusterPerformanceData($selectedYear, $selectedReport, $clusters);

        $Page = 'Cluster_Perfomance.Overview';
        return view('scrn', compact('Page', 'performanceData', 'report', 'selectedYear', 'selectedReport', 'clusters'));
    }

    /**
     * Generate cluster-level performance data.
     * For each cluster, loop through all performance indicators that the cluster is responsible for.
     * Calculate the indicator score and status; then aggregate overall counts.
     *
     * Additionally, if the current user is not an Admin, only the cluster matching the
     * current user’s ClusterID will be processed.
     */
    private function Ecsa_CP_getClusterPerformanceData($year, $reportId, $clusters)
    {
        // Retrieve all performance indicators.
        $indicators = DB::table('performance_indicators')->get();

        // Get all cluster IDs (from the clusters table).
        $allClusterIDs = $clusters->pluck('ClusterID')->toArray();

        // --- Enforce Auth Logic ---
        $currentUser = Auth::user();
        $isAdmin     = ($currentUser->AccountRole === 'Admin');
        if (! $isAdmin) {
            // For non-admin users, filter clusters to only include the user's attached cluster.
            $clusters = $clusters->filter(function ($cluster) use ($currentUser) {
                return $cluster->ClusterID === $currentUser->ClusterID;
            });
            // Update the list of all cluster IDs accordingly.
            $allClusterIDs = $clusters->pluck('ClusterID')->toArray();
        }
        // --- End Auth Logic ---

        $clusterPerformance = [];

        // Loop through each (filtered) cluster.
        foreach ($clusters as $cluster) {
            $clusterID            = $cluster->ClusterID;
            $applicableIndicators = [];
            $metCount             = 0;
            $progressingCount     = 0;
            $notPerformingCount   = 0;
            $totalIndicators      = 0;
            $missingReports       = [];

            // Loop through every performance indicator.
            foreach ($indicators as $indicator) {
                // Decode the responsible clusters.
                $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
                if (! is_array($responsibleClusters)) {
                    $responsibleClusters = [];
                }
                // If indicator expects reports from all clusters/projects, then override responsibleClusters.
                if (in_array("All clusters/projects", $responsibleClusters)) {
                    $responsibleClusters = $allClusterIDs;
                }
                // Only include this indicator if the cluster is among the responsible ones.
                if (! in_array($clusterID, $responsibleClusters)) {
                    continue;
                }

                $totalIndicators++;
                $baseline = $this->Ecsa_CP_getBaselineForYear($indicator->id, $year);
                $target   = $this->Ecsa_CP_getTargetForYear($indicator->id, $year);
                $score    = $this->Ecsa_CP_calculateScore($indicator, $reportId, $clusterID);
                $status   = $this->Ecsa_CP_calculateStatus($score, $baseline, $target, $indicator);

                // Get reported cluster IDs for this indicator.
                $reportedClusters = $this->Ecsa_CP_getReportedClusterIDs($indicator->id, $reportId);
                // Determine missing clusters for this indicator.
                $missing = array_values(array_diff($responsibleClusters, $reportedClusters));
                if (! empty($missing)) {
                    $missingReports[] = [
                        'indicator'       => $indicator->Indicator_Name,
                        'missingClusters' => $missing,
                    ];
                }

                // Count the indicator's status.
                if ($status === 'met') {
                    $metCount++;
                } elseif ($status === 'progressing') {
                    $progressingCount++;
                } elseif ($status === 'not performing') {
                    $notPerformingCount++;
                }

                $applicableIndicators[] = [
                    'name'         => $indicator->Indicator_Name,
                    'baseline'     => $baseline,
                    'target'       => $target,
                    'score'        => $score,
                    'status'       => $status,
                    'responseType' => $indicator->ResponseType,
                ];
            }

            // Calculate overall percentages (if there are any applicable indicators).
            $overallMet           = ($totalIndicators > 0) ? round(($metCount / $totalIndicators) * 100, 1) : 0;
            $overallProgressing   = ($totalIndicators > 0) ? round(($progressingCount / $totalIndicators) * 100, 1) : 0;
            $overallNotPerforming = ($totalIndicators > 0) ? round(($notPerformingCount / $totalIndicators) * 100, 1) : 0;

            $clusterPerformance[$clusterID] = [
                'clusterName'                    => $cluster->Cluster_Name,
                'totalIndicators'                => $totalIndicators,
                'metCount'                       => $metCount,
                'progressingCount'               => $progressingCount,
                'notPerformingCount'             => $notPerformingCount,
                'overallMetPercentage'           => $overallMet,
                'overallProgressingPercentage'   => $overallProgressing,
                'overallNotPerformingPercentage' => $overallNotPerforming,
                'missingReports'                 => $missingReports,
                'indicators'                     => $applicableIndicators,
            ];
        }

        return $clusterPerformance;
    }

    /**
     * Retrieve baseline for an indicator (always stored in Baseline_2023_2024).
     */
    private function Ecsa_CP_getBaselineForYear($indicatorId, $year)
    {
        return DB::table('performance_indicators')
            ->where('id', $indicatorId)
            ->value('Baseline_2023_2024');
    }

    /**
     * Retrieve target for an indicator based on the selected year.
     */
    private function Ecsa_CP_getTargetForYear($indicatorId, $year)
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
     * Calculate the score for an indicator for a given cluster.
     * For numeric indicators, sum responses (filtered by cluster).
     * For Yes/No or Boolean indicators, count affirmative responses and return percentage.
     */
    private function Ecsa_CP_calculateScore($indicator, $reportId, $clusterId)
    {
        $query = DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicator->id)
            ->where('ReportingID', $reportId)
            ->where('ClusterID', $clusterId);

        $responses = $query->pluck('Response')->toArray();

        switch ($indicator->ResponseType) {
            case 'Number':
                return array_sum($responses);
            case 'Yes/No':
            case 'Boolean':
                $affirmativeCount = count(array_filter($responses, function ($response) {
                    return in_array(strtolower($response), ['yes', 'true', '1']);
                }));
                $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);
                $expectedCount       = count($responsibleClusters);
                return ($expectedCount > 0) ? ($affirmativeCount / $expectedCount * 100) : 0;
            default:
                return null;
        }
    }

    /**
     * Determine the indicator's performance status.
     */
    private function Ecsa_CP_calculateStatus($score, $baseline, $target, $indicator)
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
    private function Ecsa_CP_getReportedClusterIDs($indicatorId, $reportId)
    {
        return DB::table('cluster_performance_mappings')
            ->where('IndicatorID', $indicatorId)
            ->where('ReportingID', $reportId)
            ->pluck('ClusterID')
            ->toArray();
    }
}