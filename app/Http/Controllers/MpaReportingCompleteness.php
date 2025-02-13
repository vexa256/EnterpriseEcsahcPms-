<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MpaReportingCompleteness extends Controller
{
    public function index(Request $request)
    {
        /**
         * 1) Enforce Access Policy
         *    - MPA + Admin => see ALL entities
         *    - MPA + Non-Admin => only own EntityID
         *    - Others => redirect with error
         */
        $user = Auth::user();

        // Check if user->UserType is MPA
        if ($user->UserType !== 'MPA') {
            return Redirect::route('mpa.reports.completeness.select_year')
                ->with('error', 'You are not authorized to view this resource.');
        }

        // Distinguish MPA Admin vs. MPA Non-Admin
        $isMpaAdmin    = ($user->UserType === 'MPA' && $user->AccountRole === 'Admin');
        $isMpaNonAdmin = ($user->UserType === 'MPA' && $user->AccountRole !== 'Admin');

        if (! $isMpaAdmin && ! $isMpaNonAdmin) {
            // Outside the two conditions => block
            return Redirect::route('mpa.reports.completeness.select_year')
                ->with('error', 'You are not authorized to view this resource.');
        }

        /**
         * 2) Existing logic to handle "select year" step
         */
        if (! $request->has('reporting_year')) {
            $years = DB::table('mpa_timelines')
                ->select('Year')
                ->distinct()
                ->orderBy('Year', 'desc')
                ->pluck('Year');

            return view('scrn', [
                'Page'  => 'MpaReports.CompletenessSelectyear',
                'years' => $years,
            ]);
        }

        $selectedYear = $request->input('reporting_year');

        // For continuity, let's keep $isAdmin (used by the Blade view)
        // as "true if MPA + Admin, false otherwise."
        $isAdmin = $isMpaAdmin;

        /**
         * 3) Fetch Entities based on role
         */
        if ($isMpaAdmin) {
            // MPA + Admin => ALL entities
            $entities = DB::table('mpa_entities')->get();
        } else {
            // MPA Non-Admin => only own entity
            if (! $user->EntityID) {
                return Redirect::route('entity.select')
                    ->with('error', 'No entity is associated with your account.');
            }
            $entities = DB::table('mpa_entities')
                ->where('EntityID', $user->EntityID)
                ->get();
        }

        /**
         * 4) Fetch timelines for chosen year
         */
        $timelines = DB::table('mpa_timelines')
            ->where('Year', $selectedYear)
            ->orderBy('ReportingID')
            ->get();

        if ($timelines->isEmpty()) {
            return Redirect::route('mpa.reports.completeness.select_year')
                ->with('error', 'No reporting timelines found for the selected year.');
        }

        /**
         * 5) Build analytics data
         */
        $analyticsData = [];

        foreach ($timelines as $timeline) {
            // Allowed reporting types for this timeline
            $allowedTypes      = $this->getReportingTypes($timeline);
            $timelineAnalytics = ['timeline' => $timeline, 'entities' => []];

            foreach ($entities as $entity) {
                // Query for matching indicators
                $indicatorQuery = DB::table('mpa_indicators')
                    ->whereIn('ReportingPeriod', $allowedTypes);

                if ($entity->EntityID === 'IGAD') {
                    $indicatorQuery->where('EntityID', $entity->EntityID);
                } else {
                    // (EntityID == current OR EntityID == 'RRF')
                    $indicatorQuery->where(function ($q) use ($entity) {
                        $q->where('EntityID', $entity->EntityID)
                            ->orWhere('EntityID', 'RRF');
                    });
                }

                $expectedIndicators = $indicatorQuery->get();
                $expectedCount      = $expectedIndicators->count();
                if ($expectedCount === 0) {
                    continue; // skip if no matching indicators
                }

                // Actual reports submitted
                $reportedReports = DB::table('mpa_reports')
                    ->where('EntityID', $entity->EntityID)
                    ->where('ReportingID', $timeline->ReportingID)
                    ->get();
                $reportedCount = $reportedReports->count();

                // Identify missing
                $expectedIDs       = $expectedIndicators->pluck('IID')->toArray();
                $reportedIDs       = $reportedReports->pluck('IID')->toArray();
                $missingIDs        = array_diff($expectedIDs, $reportedIDs);
                $missingIndicators = $expectedIndicators->filter(function ($ind) use ($missingIDs) {
                    return in_array($ind->IID, $missingIDs);
                });

                // Completeness
                $completeness = ($expectedCount > 0)
                ? round(($reportedCount / $expectedCount) * 100, 2)
                : 0;

                // Historical data (strictly matching $allowedTypes)
                $historicalData = [];
                foreach ($expectedIndicators as $indicator) {
                    $history = DB::table('mpa_reports')
                        ->join('mpa_timelines', 'mpa_reports.ReportingID', '=', 'mpa_timelines.ReportingID')
                        ->select(
                            'mpa_timelines.ReportName',
                            'mpa_timelines.Year',
                            'mpa_reports.Response',
                            'mpa_reports.Comments',
                            'mpa_reports.ReportedBy'
                        )
                        ->where('mpa_reports.IID', $indicator->IID)
                        ->whereIn('mpa_timelines.Type', $allowedTypes)
                        ->orderBy('mpa_timelines.Year', 'desc')
                        ->get();

                    $historicalData[$indicator->IID] = $history;
                }

                // Entity data
                $entityData = [
                    'entity'             => $entity,
                    'expectedCount'      => $expectedCount,
                    'reportedCount'      => $reportedCount,
                    'completeness'       => $completeness,
                    'missingIndicators'  => $missingIndicators,
                    'expectedIndicators' => $expectedIndicators,
                    'reportedReports'    => $reportedReports,
                    'historicalData'     => $historicalData,
                ];

                $timelineAnalytics['entities'][] = $entityData;
            }

            if (! empty($timelineAnalytics['entities'])) {
                $analyticsData[] = $timelineAnalytics;
            }
        }

        if (empty($analyticsData)) {
            return Redirect::route('mpa.reports.completeness.select_year')
                ->with('error', 'No indicators matching the selected reporting year and criteria were found.');
        }

        /**
         * 6) Return the view as before
         */
        return view('scrn', [
            'Page'          => 'MpaReports.ReportingCompleteness',
            'analyticsData' => $analyticsData,
            'selectedYear'  => $selectedYear,
            'isAdmin'       => $isAdmin, // for the blade
            'user'          => $user,
        ]);
    }

    /**
     * Keep your existing getReportingTypes() logic
     */
    private function getReportingTypes($timeline)
    {
        $reportingTypes = [$timeline->Type];

        if ($timeline->Type === 'Bi-Annual' && $this->isLastBiAnnualReporting($timeline)) {
            $reportingTypes[] = 'Annually Reported';
        }

        return $reportingTypes;
    }

    private function isLastBiAnnualReporting($timeline)
    {
        if ($timeline->Type !== 'Bi-Annual') {
            return false;
        }

        $laterCount = DB::table('mpa_timelines')
            ->where('Year', $timeline->Year)
            ->where('Type', 'Bi-Annual')
            ->where('ReportingID', '>', $timeline->ReportingID)
            ->count();

        return ($laterCount === 0);
    }
}