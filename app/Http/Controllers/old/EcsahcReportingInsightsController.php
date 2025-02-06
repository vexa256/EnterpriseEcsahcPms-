<?php
namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class EcsahcReportingInsightsController extends Controller
{
    // Step 1: Select Year
    public function EcsaInsightsSelectYear()
    {
        $years = DB::table('ecsahc_timelines')->distinct()->pluck('Year');
        return view('scrn', [
            "Desc"  => "Select the year for reporting",
            "Page"  => "EcsaAnalytics.EcsaInsightsSelectYear",
            "years" => $years,
        ]);
    }

    // Step 2: Select Report
    public function EcsaInsightsSelectReport(Request $request)
    {
        $request->validate([
            'Year' => 'required|integer',
        ]);

        $reports = DB::table('ecsahc_timelines')
            ->where('Year', $request->Year)
            ->get();

        if ($reports->isEmpty()) {
            return redirect()->route('EcsaInsightsSelectYear')->with('error', 'No reports found for the selected year.');
        }

        return view('scrn', [
            "Desc"         => "Select the report for the selected year",
            "Page"         => "EcsaAnalytics.EcsaInsightsSelectReport",
            "reports"      => $reports,
            "selectedYear" => $request->Year,
        ]);
    }

    // Step 3: Generate Summary Report
    public function EcsaInsightsGenerateSummary(Request $request)
    {
        $request->validate([
            'Year'        => 'required|integer',
            'ReportingID' => 'required|string|exists:ecsahc_timelines,ReportingID',
        ]);

        $reportingID = $request->ReportingID;

        // Fetch all indicators and their responsible clusters
        $indicators = DB::table('performance_indicators')
            ->join('strategic_objectives', 'performance_indicators.SO_ID', '=', 'strategic_objectives.StrategicObjectiveID')
            ->select(
                'performance_indicators.id as IndicatorID',
                'performance_indicators.Indicator_Name',
                'performance_indicators.Responsible_Cluster',
                'strategic_objectives.SO_Name'
            )
            ->get();

        // Prepare data structure for indicators and clusters
        $indicatorClusterMapping = [];
        foreach ($indicators as $indicator) {
            $clusters = json_decode($indicator->Responsible_Cluster, true);
            foreach ($clusters as $clusterID) {
                $indicatorClusterMapping[$clusterID][$indicator->IndicatorID] = [
                    'IndicatorName'      => $indicator->Indicator_Name,
                    'StrategicObjective' => $indicator->SO_Name,
                    'Reported'           => false,
                ];
            }
        }

        // Check which clusters have reported on each indicator
        $reportedData = DB::table('cluster_performance_mappings')
            ->where('ReportingID', $reportingID)
            ->get();

        foreach ($reportedData as $data) {
            if (isset($indicatorClusterMapping[$data->ClusterID][$data->IndicatorID])) {
                $indicatorClusterMapping[$data->ClusterID][$data->IndicatorID]['Reported'] = true;
            }
        }

        // Calculate overall report completion
        $totalClusters    = count($indicatorClusterMapping);
        $reportedClusters = 0;

        foreach ($indicatorClusterMapping as $clusterID => $clusterIndicators) {
            $allReported = true;
            foreach ($clusterIndicators as $indicatorData) {
                if (! $indicatorData['Reported']) {
                    $allReported = false;
                    break;
                }
            }
            if ($allReported) {
                $reportedClusters++;
            }
        }

        $completionPercentage = ($totalClusters > 0) ? ($reportedClusters / $totalClusters) * 100 : 0;

        return view('scrn', [
            "Desc"                    => "Reporting Summary",
            "Page"                    => "EcsaAnalytics.EcsaInsightsReportSummary",
            "indicatorClusterMapping" => $indicatorClusterMapping,
            "completionPercentage"    => $completionPercentage,
            "totalClusters"           => $totalClusters,
            "reportedClusters"        => $reportedClusters,
        ]);
    }

    // Step 4: Dashboard with Graphs
    public function EcsaInsightsDashboard(Request $request)
    {
        $year = DB::table('ecsahc_timelines')
            ->where('ReportingID', $request->ReportingID)
            ->value('Year');

        $totalClusters = DB::table('clusters')->count();

        $reportedClusters = DB::table('cluster_performance_mappings')
            ->distinct('ClusterID')
            ->where('ReportingID', $request->ReportingID)
            ->count();

        $activeReports = DB::table('ecsahc_timelines')
            ->where('status', 'In Progress')
            ->count();

        $chartData = [
            'labels' => ['Clusters Reported', 'Clusters Not Reported'],
            'series' => [
                $reportedClusters,
                $totalClusters - $reportedClusters,
            ],
        ];

        $strategicObjectives = DB::table('strategic_objectives')
            ->select('SO_Name')
            ->selectRaw('(SELECT COUNT(*) FROM cluster_performance_mappings WHERE cluster_performance_mappings.SO_ID = strategic_objectives.StrategicObjectiveID) as reported_indicators')
            ->selectRaw('(SELECT COUNT(*) FROM performance_indicators WHERE performance_indicators.SO_ID = strategic_objectives.StrategicObjectiveID) as total_indicators')
            ->selectRaw('ROUND((SELECT COUNT(*) FROM cluster_performance_mappings WHERE cluster_performance_mappings.SO_ID = strategic_objectives.StrategicObjectiveID) / (SELECT COUNT(*) FROM performance_indicators WHERE performance_indicators.SO_ID = strategic_objectives.StrategicObjectiveID) * 100, 1) as completion_rate')
            ->get();

        $performanceIndicators = DB::table('performance_indicators')
            ->select('Indicator_Name', 'Baseline_2023_2024', 'Target_Year1', 'Target_Year2', 'Target_Year3')
            ->selectRaw('(SELECT SUM(CAST(Response AS DECIMAL(10,2))) FROM cluster_performance_mappings WHERE cluster_performance_mappings.IndicatorID = performance_indicators.id AND cluster_performance_mappings.ReportingID = ?) as current_value', [$request->ReportingID])
            ->selectRaw('ROUND(((SELECT SUM(CAST(Response AS DECIMAL(10,2))) FROM cluster_performance_mappings WHERE cluster_performance_mappings.IndicatorID = performance_indicators.id AND cluster_performance_mappings.ReportingID = ?) / NULLIF(Target_Year1, 0)) * 100, 1) as progress', [$request->ReportingID])
            ->get();

        return view('scrn', [
            "Desc"                  => "Dashboard",
            "Page"                  => "EcsaAnalytics.EcsaInsightsDashboard",
            "chartData"             => $chartData,
            "totalClusters"         => $totalClusters,
            "activeReports"         => $activeReports,
            "strategicObjectives"   => $strategicObjectives,
            "performanceIndicators" => $performanceIndicators,
        ]);
    }

    // Step 5: Indicator Details
    public function EcsaInsightsIndicatorDetails(Request $request)
    {
        $request->validate([
            'IndicatorID' => 'required|exists:performance_indicators,id',
            'ReportingID' => 'required|exists:ecsahc_timelines,ReportingID',
        ]);

        $indicator = DB::table('performance_indicators')
            ->where('id', $request->IndicatorID)
            ->first();

        if (! $indicator) {
            return redirect()->back()->with('error', 'Indicator not found.');
        }

        // Fetch all clusters responsible for this indicator
        $responsibleClusters = json_decode($indicator->Responsible_Cluster, true);

        // Fetch responses from cluster_performance_mappings
        $responses = DB::table('cluster_performance_mappings')
            ->join('clusters', 'cluster_performance_mappings.ClusterID', '=', 'clusters.ClusterID')
            ->where('IndicatorID', $request->IndicatorID)
            ->where('ReportingID', $request->ReportingID)
            ->select(
                'clusters.Cluster_Name',
                'clusters.ClusterID',
                'cluster_performance_mappings.Response',
                'cluster_performance_mappings.ReportingComment'
            )
            ->get()
            ->keyBy('ClusterID');

        // Prepare data for display
        $clusterStatus = [];
        foreach ($responsibleClusters as $clusterID) {
            $cluster = DB::table('clusters')->where('ClusterID', $clusterID)->first();
            if ($cluster) {
                $clusterStatus[$clusterID] = [
                    'ClusterName' => $cluster->Cluster_Name,
                    'Response'    => $responses->has($clusterID) ? $responses[$clusterID]->Response : null,
                    'Comment'     => $responses->has($clusterID) ? $responses[$clusterID]->ReportingComment : null,
                    'Reported'    => $responses->has($clusterID),
                ];
            }
        }

        return view('scrn', [
            "Desc"          => "Indicator Details",
            "Page"          => "EcsaAnalytics.EcsaInsightsIndicatorDetails",
            "indicator"     => $indicator,
            "clusterStatus" => $clusterStatus,
        ]);
    }

    // Step 6: Target Achievement Analysis
    public function EcsaInsightsTargetAnalysis(Request $request)
    {
        $request->validate([
            'ReportingID' => 'required|exists:ecsahc_timelines,ReportingID',
        ]);

        $year = DB::table('ecsahc_timelines')
            ->where('ReportingID', $request->ReportingID)
            ->value('Year');

        $targetColumn = match ($year) {
            2024 => 'Target_Year1',
            2025 => 'Target_Year2',
            2026 => 'Target_Year3',
            default => null,
        };

        if (! $targetColumn) {
            return redirect()->back()->with('error', 'Invalid year for target analysis.');
        }

        // Fetch indicators and their performance against targets
        $indicators = DB::table('performance_indicators')
            ->leftJoin('cluster_performance_mappings', function ($join) use ($request) {
                $join->on('performance_indicators.id', '=', 'cluster_performance_mappings.IndicatorID')
                    ->where('cluster_performance_mappings.ReportingID', $request->ReportingID);
            })
            ->select(
                'performance_indicators.id as IndicatorID',
                'performance_indicators.Indicator_Name',
                'performance_indicators.' . $targetColumn . ' as Target',
                DB::raw('SUM(cluster_performance_mappings.Response) as TotalResponse')
            )
            ->groupBy('performance_indicators.id', 'performance_indicators.Indicator_Name', 'performance_indicators.' . $targetColumn)
            ->get();

        $meetingTargets = $indicators->filter(function ($indicator) {
            return $indicator->TotalResponse >= $indicator->Target;
        });

        $nonPerforming = $indicators->filter(function ($indicator) {
            return $indicator->TotalResponse < $indicator->Target;
        });

        return view('scrn', [
            "Desc"           => "Target Achievement Analysis",
            "Page"           => "EcsaAnalytics.EcsaInsightsTargetAnalysis",
            "indicators"     => $indicators,
            "meetingTargets" => $meetingTargets,
            "nonPerforming"  => $nonPerforming,
        ]);
    }
}