<?php
namespace App\Http\Controllers;

use App\Exports\RRFReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class RRFReportController extends Controller
{
    /**
     * STEP 1: Select Report Type.
     *
     * This method retrieves available report types based solely on reports tied to RRF indicators.
     * It joins mpa_reports with mpa_indicators (where i.EntityID = 'RRF') and then extracts distinct
     * ReportingPeriod values. If both "Annually Reported" and "Bi-Annual" are available, it checks
     * for a bi-annual record flagged as LastBiAnnual (or falls back to the most recent one) and adds
     * a "Combined" option.
     */
    public function selectReport(Request $request)
    {
        $user = Auth::user();
        if ($user->UserType !== 'MPA') {
            return Redirect::route('home')
                ->with('error', 'You are not authorized to view RRF reports.');
        }

        // Get distinct ReportingPeriod values from mpa_reports joined with mpa_indicators for RRF indicators.
        $availableReportTypes = DB::table('mpa_reports as r')
            ->join('mpa_indicators as i', 'r.IID', '=', 'i.IID')
            ->where('i.EntityID', 'RRF')
            ->select('i.ReportingPeriod')
            ->distinct()
            ->pluck('ReportingPeriod')
            ->toArray();

        // Trim whitespace
        $availableReportTypes = array_map('trim', $availableReportTypes);

        // If both "Annually Reported" and "Bi-Annual" are available, add "Combined".
        if (in_array('Bi-Annual', $availableReportTypes) && in_array('Annually Reported', $availableReportTypes)) {
            // Check for a LastBiAnnual record; if not available, fallback to the most recent.
            $lastBiAnnualExists = DB::table('mpa_timelines')
                ->where('Type', 'Bi-Annual')
                ->where('LastBiAnnual', 1)
                ->exists();

            if ($lastBiAnnualExists) {
                $availableReportTypes[] = 'Combined';
            } else {
                $availableReportTypes[] = 'Combined';
            }
        }

        $availableReportTypes = array_values(array_unique($availableReportTypes));

        return view('scrn', [
            'Page'        => 'MpaReports.RRFReportSelectReport',
            'reportTypes' => $availableReportTypes,
            'user'        => $user,
        ]);
    }

    /**
     * STEP 2: Select Reporting Year.
     *
     * This method builds the list of available years from the mpa_reports table joined with mpa_timelines.
     * Only those years with at least one report are included.
     */
    public function selectYear(Request $request)
    {

        $selectedReportType = $request->input('report_type');

        if (! $selectedReportType) {
            return Redirect::route('rrf.report.selectReport')
                ->with('error', 'Please select a report type.');
        }

        // Join mpa_reports with mpa_timelines and get distinct years.
        $years = DB::table('mpa_reports as r')
            ->join('mpa_timelines as t', 'r.ReportingID', '=', 't.ReportingID')
            ->select('t.Year')
            ->distinct()
            ->orderBy('t.Year', 'desc')
            ->pluck('Year');

        return view('scrn', [
            'Page'       => 'MpaReports.RRFReportSelectYear',
            'years'      => $years,
            'reportType' => $selectedReportType,
        ]);
    }

    /**
     * STEP 3: RRF Dashboard.
     *
     * This method aggregates data exclusively for the selected year.
     * It retrieves timelines (filtered by the selected year), RRF indicators, and then computes:
     *   - Aggregated achieved values (Yes/No percentages or sums),
     *   - Historical data (only for the selected year),
     *   - Target values (mapped by year),
     *   - Differences (Achieved – Target), and
     *   - Reporting completeness.
     *
     * In "Combined" mode, it merges all annual timelines with the most appropriate bi‑annual timeline
     * (using the LastBiAnnual flag or fallback).
     */
    public function dashboard(Request $request)
    {

        // dd($request->has('reporting_year'));

        if (! $request->has('report_type') || ! $request->has('reporting_year')) {
            return Redirect::route('rrf.report.selectReport')
                ->with('error', 'Please select both a report type and a reporting year.');
        }
        $selectedReportType = $request->input('report_type');
        $selectedYear       = $request->input('reporting_year');

        $user = Auth::user();
        if ($user->UserType !== 'MPA') {
            return Redirect::route('home')
                ->with('error', 'You are not authorized to view the RRF dashboard.');
        }

        // Retrieve reporting countries from mpa_entities (only countries, not ECSA-HC or IGAD).
        $reportingCountries = DB::table('mpa_entities')
            ->whereNotIn('EntityID', ['ECSA-HC', 'IGAD'])
            ->get();
        $totalCountries = $reportingCountries->count();

        // Retrieve timelines for the selected year.
        if ($selectedReportType === 'Combined') {
            $annualTimelines = DB::table('mpa_timelines')
                ->where('Year', $selectedYear)
                ->where('Type', 'Annually Reported')
                ->get();
            $biAnnualTimeline = DB::table('mpa_timelines')
                ->where('Year', $selectedYear)
                ->where('Type', 'Bi-Annual')
                ->where('LastBiAnnual', 1)
                ->first();
            if (! $biAnnualTimeline) {
                $biAnnualTimeline = DB::table('mpa_timelines')
                    ->where('Year', $selectedYear)
                    ->where('Type', 'Bi-Annual')
                    ->orderBy('ReportingID', 'desc')
                    ->first();
            }
            $timelines = $annualTimelines;
            if ($biAnnualTimeline) {
                $timelines->push($biAnnualTimeline);
            }
        } else {
            $timelines = DB::table('mpa_timelines')
                ->where('Year', $selectedYear)
                ->where('Type', $selectedReportType)
                ->get();
        }
        if ($timelines->isEmpty()) {
            return Redirect::route('rrf.report.selectYear')
                ->with('error', 'No reporting timelines found for the selected year and report type.');
        }

        // Retrieve all RRF indicators (where EntityID = 'RRF').
        $rrfIndicators = DB::table('mpa_indicators')
            ->where('EntityID', 'RRF')
            ->get();

        // Build analytics data.
        $analyticsData = [];
        foreach ($timelines as $timeline) {
            $timelineData = [
                'timeline'   => $timeline,
                'indicators' => [],
            ];

            foreach ($rrfIndicators as $indicator) {
                // Retrieve reports for this indicator and timeline (for the selected year only).
                $reports = DB::table('mpa_reports')
                    ->where('IID', $indicator->IID)
                    ->where('ReportingID', $timeline->ReportingID)
                    ->whereNotIn('EntityID', ['ECSA-HC', 'IGAD'])
                    ->get();

                // Compute aggregated achieved value.
                $computedValue = null;
                if ($indicator->ResponseType === 'Yes/No') {
                    $yesCount = $reports->filter(function ($report) {
                        return strtolower(trim($report->Response)) === 'yes';
                    })->count();
                    $noCount       = $reports->count() - $yesCount;
                    $yesPercentage = ($totalCountries > 0) ? round(($yesCount / $totalCountries) * 100, 2) : 0;
                    $computedValue = [
                        'yesCount'      => $yesCount,
                        'noCount'       => $noCount,
                        'yesPercentage' => $yesPercentage,
                    ];
                } elseif ($indicator->ResponseType === 'Number') {
                    $sum = 0;
                    foreach ($reports as $report) {
                        $value = floatval($report->Response);
                        if ($indicator->meta_conversion_method === 'strip_percentage') {
                            $value = floatval($value);
                        }
                        $sum += $value;
                    }
                    $computedValue = ['sum' => $sum];
                } else {
                    $computedValue = ['rawResponses' => $reports->pluck('Response')->toArray()];
                }

                // Retrieve historical data for this indicator for the selected year and current timeline type.
                $historicalData = DB::table('mpa_reports')
                    ->join('mpa_timelines', 'mpa_reports.ReportingID', '=', 'mpa_timelines.ReportingID')
                    ->select(
                        'mpa_timelines.ReportName',
                        'mpa_timelines.Year',
                        'mpa_reports.Response',
                        'mpa_reports.Comments',
                        'mpa_reports.EntityID',
                    )
                    ->where('mpa_reports.IID', $indicator->IID)
                    ->where('mpa_timelines.Year', $selectedYear)
                    ->where('mpa_timelines.Type', $timeline->Type)
                    ->orderBy('mpa_timelines.ReportingID', 'asc')
                    ->get();

                // Retrieve the target value based on the selected year.
                $targetValue = $this->getIndicatorTarget($indicator, $selectedYear);

                // Compute achieved value and difference.
                $achieved   = null;
                $difference = null;
                if ($indicator->ResponseType === 'Yes/No') {
                    $achieved = $computedValue['yesPercentage'];
                    if (is_numeric($targetValue)) {
                        $difference = round($achieved - floatval($targetValue), 2);
                    }
                } elseif ($indicator->ResponseType === 'Number') {
                    $achieved = isset($computedValue['sum']) ? $computedValue['sum'] : null;
                    if (is_numeric($targetValue)) {
                        $difference = round($achieved - floatval($targetValue), 2);
                    }
                }

                $timelineData['indicators'][] = [
                    'indicator'      => $indicator,
                    'computedValue'  => $computedValue,
                    'targetValue'    => $targetValue,
                    'achieved'       => $achieved,
                    'difference'     => $difference,
                    'reports'        => $reports,
                    'historicalData' => $historicalData,
                ];
            }
            $analyticsData[] = $timelineData;
        }

        // Compute reporting completeness per timeline.
        $completenessData = [];
        foreach ($timelines as $timeline) {
            $expectedCount = $totalCountries * $rrfIndicators->count();
            $reportedCount = 0;
            foreach ($rrfIndicators as $indicator) {
                $count = DB::table('mpa_reports')
                    ->where('IID', $indicator->IID)
                    ->where('ReportingID', $timeline->ReportingID)
                    ->whereNotIn('EntityID', ['ECSA-HC', 'IGAD'])
                    ->count();
                $reportedCount += $count;
            }
            $completeness       = ($expectedCount > 0) ? round(($reportedCount / $expectedCount) * 100, 2) : 0;
            $completenessData[] = [
                'timeline'      => $timeline,
                'expectedCount' => $expectedCount,
                'reportedCount' => $reportedCount,
                'completeness'  => $completeness,
            ];
        }

        return view('scrn', [
            'Page'             => 'MpaReports.RRFReportDashboard',
            'analyticsData'    => $analyticsData,
            'completenessData' => $completenessData,
            'selectedYear'     => $selectedYear,
            'reportType'       => $selectedReportType,
            'totalCountries'   => $totalCountries,
            'user'             => $user,
            'isAdmin'          => ($user->AccountRole === 'Admin'),
        ]);
    }

    /**
     * Export the RRF Report to Excel with detailed metrics and conditional formatting.
     *
     * This method uses the Maatwebsite\Excel package to export a detailed .xlsx file.
     * The exported file includes data exclusively for the selected year.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        if (! $request->has('report_type') || ! $request->has('reporting_year')) {
            return Redirect::route('rrf.report.selectReport')
                ->with('error', 'Please select a reporting year and report type for export.');
        }
        $selectedYear       = $request->input('reporting_year');
        $selectedReportType = $request->input('report_type');

        $filename = 'RRF_Report_' . $selectedYear . '_' . str_replace(' ', '_', $selectedReportType) . '.xlsx';

        return Excel::download(new RRFReportExport($selectedYear, $selectedReportType), $filename);
    }

    /**
     * Helper method to get the target value for an indicator based on the selected year.
     *
     * Maps the selected year to the appropriate target column.
     *
     * @param  object $indicator  The indicator record.
     * @param  string $selectedYear  The reporting year (e.g., "2025").
     * @return float  The target value.
     */
    private function getIndicatorTarget($indicator, $selectedYear)
    {
        $mapping = [
            '2024' => 'TargetYearOne2024',
            '2025' => 'TargetYearTwo2025',
            '2026' => 'TargetYearThree2026',
            '2027' => 'TargetYearFour2027',
            '2028' => 'TargetYearFive2028',
            '2029' => 'TargetYearSix2029',
            '2030' => 'TargetYearSeven2030',
        ];

        if (array_key_exists($selectedYear, $mapping)) {
            $column = $mapping[$selectedYear];
            if (isset($indicator->{$column}) && $indicator->{$column} !== null && $indicator->{$column} !== '') {
                return floatval(str_replace('%', '', $indicator->{$column}));
            }
        }
        if (isset($indicator->ExpectedTarget) && $indicator->ExpectedTarget !== null) {
            return floatval(str_replace('%', '', $indicator->ExpectedTarget));
        }
        return 0;
    }
}