<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EcsaReportingController extends Controller
{
    /**
     * Validate request and redirect with errors if validation fails.
     */
    private function validateAndRedirect($request, $rules, $redirectRoute)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // Redirect using the new route name.
            return redirect()->route('Ecsa_' . $redirectRoute)->withErrors($validator)->withInput();
        }
        return null;
    }

    private function getUser($UserID)
    {
        return DB::table('users')->where('UserID', $UserID)->first();
    }

    private function getCluster($ClusterID)
    {
        return DB::table('clusters')->where('ClusterID', $ClusterID)->first();
    }

    private function getTimeline($ReportingID)
    {
        return DB::table('ecsahc_timelines')->where('ReportingID', $ReportingID)->first();
    }

    private function getStrategicObjective($StrategicObjectiveID)
    {
        // Look up strategic objective by its external identifier stored in StrategicObjectiveID column.
        return DB::table('strategic_objectives')->where('StrategicObjectiveID', $StrategicObjectiveID)->first();
    }

    public function SelectUser()
    {
        $users = DB::table('users')->where('UserType', 'ECSA-HC')->get();

        return view('scrn', [
            "Desc"  => "Select an ECSA-HC user to begin reporting",
            "Page"  => "EcsaReporting.EcsaSelectUser",
            "users" => $users,
        ]);
    }

    public function SelectCluster(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request,
            ['UserID' => 'required|exists:users,UserID'],
            'SelectUser'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        $user = $this->getUser($request->UserID);
        if (! $user) {
            return redirect()->route('Ecsa_SelectUser')->with('error', 'User not found.');
        }

        $clusters = DB::table('clusters')->where('ClusterID', $user->ClusterID)->get();
        if ($clusters->isEmpty()) {
            return redirect()->route('Ecsa_SelectUser')->with('error', 'No clusters found for the selected user.');
        }

        return view('scrn', [
            "Desc"     => "Select a cluster for reporting",
            "Page"     => "EcsaReporting.EcsaSelectCluster",
            "clusters" => $clusters,
            "user"     => $user,
            "userName" => $user->name,
        ]);
    }

    public function SelectTimeline(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request,
            [
                'UserID'    => 'required|exists:users,UserID',
                'ClusterID' => 'required|exists:clusters,ClusterID',
            ],
            'SelectCluster'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        $user    = $this->getUser($request->UserID);
        $cluster = $this->getCluster($request->ClusterID);

        if (! $user || ! $cluster) {
            return redirect()->route('Ecsa_SelectCluster')->with('error', 'User or Cluster not found.');
        }

        $timelines = DB::table('ecsahc_timelines')->where('status', 'In Progress')->get();

        return view('scrn', [
            "Desc"        => "Select a timeline for reporting",
            "Page"        => "EcsaReporting.EcsaSelectTimeline",
            "timelines"   => $timelines,
            "UserID"      => $request->UserID,
            "ClusterID"   => $request->ClusterID,
            "userName"    => $user->name,
            "clusterName" => $cluster->Cluster_Name,
        ]);
    }

    public function SelectStrategicObjective(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request,
            [
                'UserID'      => 'required|exists:users,UserID',
                'ClusterID'   => 'required|exists:clusters,ClusterID',
                'ReportingID' => 'required|exists:ecsahc_timelines,ReportingID',
            ],
            'SelectTimeline'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        $user     = $this->getUser($request->UserID);
        $cluster  = $this->getCluster($request->ClusterID);
        $timeline = $this->getTimeline($request->ReportingID);

        if (! $user || ! $cluster || ! $timeline) {
            return redirect()->route('Ecsa_SelectTimeline')->with('error', 'User, Cluster, or Timeline not found.');
        }

        // Ensure the selected ClusterID is actually assigned to the user.
        if (trim($user->ClusterID) !== trim($request->ClusterID)) {
            return redirect()->route('Ecsa_SelectCluster')->with('error', 'The selected cluster does not match the user\'s assigned cluster.');
        }

        // Fetch only strategic objectives that have at least one performance indicator
        // where the indicator's SO_ID matches the strategic objective's external identifier
        // and its Responsible_Cluster JSON array contains the selected ClusterID.
        $strategicObjectives = DB::table('strategic_objectives')
            ->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->from('performance_indicators')
                    ->whereColumn('performance_indicators.SO_ID', 'strategic_objectives.StrategicObjectiveID')
                    ->whereJsonContains('performance_indicators.Responsible_Cluster', trim($request->ClusterID));
            })
            ->get();

        return view('scrn', [
            "Desc"                => "Select a strategic objective for reporting",
            "Page"                => "EcsaReporting.EcsaStrategicObjectives",
            "strategicObjectives" => $strategicObjectives,
            "UserID"              => $request->UserID,
            "ClusterID"           => $request->ClusterID,
            "ReportingID"         => $request->ReportingID,
            "userName"            => $user->name,
            "clusterName"         => $cluster->Cluster_Name,
            "timelineName"        => $timeline->ReportName,
        ]);
    }

    public function ReportPerformanceIndicators(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request, [
                'UserID'               => 'required|exists:users,UserID',
                'ClusterID'            => 'required|exists:clusters,ClusterID',
                'ReportingID'          => 'required|exists:ecsahc_timelines,ReportingID',
                'StrategicObjectiveID' => 'required|exists:strategic_objectives,StrategicObjectiveID',
            ],
            'SelectStrategicObjective'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        $user               = $this->getUser($request->UserID);
        $cluster            = $this->getCluster($request->ClusterID);
        $timeline           = $this->getTimeline($request->ReportingID);
        $strategicObjective = $this->getStrategicObjective($request->StrategicObjectiveID);

        if (! $user || ! $cluster || ! $timeline || ! $strategicObjective) {
            return redirect()->route('Ecsa_SelectStrategicObjective')->with('error', 'Required data not found.');
        }

        // Trim incoming values to ensure consistency.
        $clusterID            = trim($request->ClusterID);
        $strategicObjectiveID = trim($request->StrategicObjectiveID);

        // Fetch performance indicators for the selected strategic objective that list the given cluster in their Responsible_Cluster JSON array.
        $indicators = DB::table('performance_indicators')
            ->where('SO_ID', $strategicObjectiveID)
            ->whereJsonContains('Responsible_Cluster', $clusterID)
            ->get();

        // If no indicators are found, redirect back with an error message.
        if ($indicators->isEmpty()) {
            return redirect()->route('Ecsa_SelectStrategicObjective')
                ->with('error', 'No performance indicators found for the selected strategic objective and cluster combination. Please verify your selection.');
        }

        $existingReports = DB::table('cluster_performance_mappings')
            ->join('users', 'cluster_performance_mappings.UserID', '=', 'users.UserID')
            ->where('cluster_performance_mappings.ClusterID', $request->ClusterID)
            ->where('cluster_performance_mappings.ReportingID', $request->ReportingID)
            ->where('cluster_performance_mappings.SO_ID', $request->StrategicObjectiveID)
            ->select('cluster_performance_mappings.*', 'users.name as reporter_name', 'users.email as reporter_email')
            ->get()
            ->keyBy('IndicatorID');

        $totalIndicators    = $indicators->count();
        $reportedIndicators = $existingReports->count();
        $progressPercentage = $totalIndicators > 0 ? ($reportedIndicators / $totalIndicators) * 100 : 0;

        return view('scrn', [
            "Desc"                 => "Report on performance indicators",
            "Page"                 => "EcsaReporting.EcsaReport",
            "indicators"           => $indicators,
            "UserID"               => $request->UserID,
            "ClusterID"            => $request->ClusterID,
            "ReportingID"          => $request->ReportingID,
            "StrategicObjectiveID" => $request->StrategicObjectiveID,
            "userName"             => $user->name,
            "clusterName"          => $cluster->Cluster_Name,
            "timelineName"         => $timeline->ReportName,
            "objectiveName"        => $strategicObjective->SO_Name . ' | ' . $strategicObjective->Description,
            "existingReports"      => $existingReports,
            "progressPercentage"   => $progressPercentage,
            "totalIndicators"      => $totalIndicators,
            "reportedIndicators"   => $reportedIndicators,
            "timelineStatus"       => $timeline->status,
        ]);
    }

    public function SavePerformanceReport(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request, [
                'UserID'               => 'required|exists:users,UserID',
                'ClusterID'            => 'required|exists:clusters,ClusterID',
                'ReportingID'          => 'required|exists:ecsahc_timelines,ReportingID',
                // Validate against the external identifier in strategic_objectives.
                'StrategicObjectiveID' => 'required|exists:strategic_objectives,StrategicObjectiveID',
                'IndicatorID'          => 'required|exists:performance_indicators,id',
                'Response'             => 'required',
                'ResponseType'         => 'required|in:Text,Number,Boolean,Yes/No',
                'Comment'              => 'nullable|string',
            ],
            'ReportPerformanceIndicators'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        // In the cluster_performance_mappings table, the key for the strategic objective is SO_ID.
        DB::table('cluster_performance_mappings')->updateOrInsert(
            [
                'ClusterID'   => $request->ClusterID,
                'ReportingID' => $request->ReportingID,
                'SO_ID'       => $request->StrategicObjectiveID,
                'UserID'      => $request->UserID,
                'IndicatorID' => $request->IndicatorID,
            ],
            [
                'Response'         => $request->Response,
                'ResponseType'     => $request->ResponseType,
                'ReportingComment' => $request->Comment,
                'updated_at'       => now(),
            ]
        );

        return redirect()->route('Ecsa_ReportPerformanceIndicators', [
            'UserID'               => $request->UserID,
            'ClusterID'            => $request->ClusterID,
            'ReportingID'          => $request->ReportingID,
            'StrategicObjectiveID' => $request->StrategicObjectiveID,
        ])->with('success', 'Report saved successfully!');
    }

    public function GetReportingSummary(Request $request)
    {
        $redirectResult = $this->validateAndRedirect(
            $request, [
                'UserID'               => 'required|exists:users,UserID',
                'ClusterID'            => 'required|exists:clusters,ClusterID',
                'ReportingID'          => 'required|exists:ecsahc_timelines,ReportingID',
                'StrategicObjectiveID' => 'required|exists:strategic_objectives,StrategicObjectiveID',
            ],
            'ReportPerformanceIndicators'
        );
        if ($redirectResult) {
            return $redirectResult;
        }

        $user               = $this->getUser($request->UserID);
        $cluster            = $this->getCluster($request->ClusterID);
        $timeline           = $this->getTimeline($request->ReportingID);
        $strategicObjective = $this->getStrategicObjective($request->StrategicObjectiveID);

        if (! $user || ! $cluster || ! $timeline || ! $strategicObjective) {
            return redirect()->route('Ecsa_ReportPerformanceIndicators')->with('error', 'Required data not found.');
        }

        $reports = DB::table('cluster_performance_mappings')
            ->where('ClusterID', $request->ClusterID)
            ->where('ReportingID', $request->ReportingID)
            ->where('SO_ID', $request->StrategicObjectiveID)
            ->where('UserID', $request->UserID)
            ->join('performance_indicators', 'cluster_performance_mappings.IndicatorID', '=', 'performance_indicators.id')
            ->select('cluster_performance_mappings.*', 'performance_indicators.Indicator_Name', 'performance_indicators.Indicator_Number')
            ->get();

        return view('scrn', [
            "Desc"                 => "Reporting Summary",
            "Page"                 => "EcsaReporting.ReportingSummary",
            "reports"              => $reports,
            "UserID"               => $request->UserID,
            "ClusterID"            => $request->ClusterID,
            "ReportingID"          => $request->ReportingID,
            "StrategicObjectiveID" => $request->StrategicObjectiveID,
            "userName"             => $user->name,
            "clusterName"          => $cluster->Cluster_Name,
            "timelineName"         => $timeline->ReportName,
            "objectiveName"        => $strategicObjective->SO_Name . '  |' . $strategicObjective->Description,
        ]);
    }
}