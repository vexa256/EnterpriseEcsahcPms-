<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MpaRRFController extends Controller
{
    /**
     * Display all Regional Results Framework (RRF) indicators (EntityID = 'RRF').
     */
    public function ShowRRFIndicators()
    {
        // Fetch all indicators where EntityID = 'RRF'
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', 'RRF')
            ->orderBy('id', 'asc')
            ->get();

        // Provide a placeholder "entity" object to keep consistent with your layout logic
        // This might not be strictly necessary, but can be useful if your Blade references `$SelectedEntity`.
        $fakeEntity = (object) [
            'EntityID' => 'RRF',
            'Entity'   => 'Regional Results Framework',
        ];

        // Return data in the same "scrn" format you use
        $data = [
            "Desc"           => "Manage Regional Results Framework Indicators",
            "Page"           => "indicators.MgtRffIndicators",    // The partial your scrn view includes
            "entities"       => DB::table('mpa_entities')->get(), // If your layout needs them
            "SelectedEntity" => $fakeEntity,                      // So Blade can display "RRF" if needed
            "indicators"     => $indicators,
        ];

        return view('scrn', $data);
    }

    /**
     * Store a new RRF Indicator (EntityID = 'RRF').
     */
    public function StoreRRFIndicator(Request $request)
    {
        // Validate
        $validated = $request->validate([
            // We do NOT require 'EntityID' from the form because we forcibly set it to 'RRF'
            'IndicatorPrimaryCategory'   => 'required|string|max:255',
            'IndicatorSecondaryCategory' => 'required|string|max:255',
            'IID'                        => 'required|string|max:255|unique:mpa_indicators,IID',
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Yes/No',
            'ReportingPeriod'            => 'nullable|string|max:50',
            'ExpectedTarget'             => 'nullable|string|max:255',
            'BaselinePAD2023'            => 'nullable|string|max:255',
            'Baseline2024'               => 'nullable|string|max:255',
            'TargetYearOne2024'          => 'nullable|string|max:255',
            'TargetYearTwo2025'          => 'nullable|string|max:255',
            'TargetYearThree2026'        => 'nullable|string|max:255',
            'TargetYearFour2027'         => 'nullable|string|max:255',
            'TargetYearFive2028'         => 'nullable|string|max:255',
            'TargetYearSix2029'          => 'nullable|string|max:255',
            'TargetYearSeven2030'        => 'nullable|string|max:255',
        ]);

        // Insert the record with EntityID = 'RRF'
        $insertedId = DB::table('mpa_indicators')->insertGetId([
            'EntityID'                   => 'RRF',
            'IndicatorPrimaryCategory'   => $validated['IndicatorPrimaryCategory'],
            'IndicatorSecondaryCategory' => $validated['IndicatorSecondaryCategory'],
            'IID'                        => $validated['IID'],
            'Indicator'                  => $validated['Indicator'],
            'IndicatorDefinition'        => $validated['IndicatorDefinition'] ?? null,
            'IndicatorQuestion'          => $validated['IndicatorQuestion'] ?? null,
            'RemarksComments'            => $validated['RemarksComments'] ?? null,
            'SourceOfData'               => $validated['SourceOfData'] ?? null,
            'ResponseType'               => $validated['ResponseType'],
            'ReportingPeriod'            => $validated['ReportingPeriod'] ?? null,
            'ExpectedTarget'             => $validated['ExpectedTarget'] ?? null,
            'BaselinePAD2023'            => $validated['BaselinePAD2023'] ?? null,
            'Baseline2024'               => $validated['Baseline2024'] ?? null,
            'TargetYearOne2024'          => $validated['TargetYearOne2024'] ?? null,
            'TargetYearTwo2025'          => $validated['TargetYearTwo2025'] ?? null,
            'TargetYearThree2026'        => $validated['TargetYearThree2026'] ?? null,
            'TargetYearFour2027'         => $validated['TargetYearFour2027'] ?? null,
            'TargetYearFive2028'         => $validated['TargetYearFive2028'] ?? null,
            'TargetYearSix2029'          => $validated['TargetYearSix2029'] ?? null,
            'TargetYearSeven2030'        => $validated['TargetYearSeven2030'] ?? null,
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);

        if ($insertedId) {
            session()->flash('success', 'RRF Indicator added successfully!');
        } else {
            session()->flash('error', 'Failed to add RRF indicator. Please try again.');
        }

        // Now re-fetch the same data (all RRF indicators)
        return $this->refreshRRFIndicatorsView();
    }

    /**
     * Update an existing RRF Indicator (EntityID = 'RRF').
     */
    public function UpdateRRFIndicator(Request $request)
    {
        $validated = $request->validate([
            'id'                         => 'required|exists:mpa_indicators,id',
            'IndicatorPrimaryCategory'   => 'required|string|max:255',
            'IndicatorSecondaryCategory' => 'required|string|max:255',
            'IID'                        => 'required|string|max:255|unique:mpa_indicators,IID,' . $request->id,
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Yes/No',
            'ReportingPeriod'            => 'nullable|string|max:50',
            'ExpectedTarget'             => 'nullable|string|max:255',
            'BaselinePAD2023'            => 'nullable|string|max:255',
            'Baseline2024'               => 'nullable|string|max:255',
            'TargetYearOne2024'          => 'nullable|string|max:255',
            'TargetYearTwo2025'          => 'nullable|string|max:255',
            'TargetYearThree2026'        => 'nullable|string|max:255',
            'TargetYearFour2027'         => 'nullable|string|max:255',
            'TargetYearFive2028'         => 'nullable|string|max:255',
            'TargetYearSix2029'          => 'nullable|string|max:255',
            'TargetYearSeven2030'        => 'nullable|string|max:255',
        ]);

        $affected = DB::table('mpa_indicators')
            ->where('id', $validated['id'])
            ->where('EntityID', 'RRF') // Ensure we only update RRF indicators
            ->update([
                'IndicatorPrimaryCategory'   => $validated['IndicatorPrimaryCategory'],
                'IndicatorSecondaryCategory' => $validated['IndicatorSecondaryCategory'],
                'IID'                        => $validated['IID'],
                'Indicator'                  => $validated['Indicator'],
                'IndicatorDefinition'        => $validated['IndicatorDefinition'] ?? null,
                'IndicatorQuestion'          => $validated['IndicatorQuestion'] ?? null,
                'RemarksComments'            => $validated['RemarksComments'] ?? null,
                'SourceOfData'               => $validated['SourceOfData'] ?? null,
                'ResponseType'               => $validated['ResponseType'],
                'ReportingPeriod'            => $validated['ReportingPeriod'] ?? null,
                'ExpectedTarget'             => $validated['ExpectedTarget'] ?? null,
                'BaselinePAD2023'            => $validated['BaselinePAD2023'] ?? null,
                'Baseline2024'               => $validated['Baseline2024'] ?? null,
                'TargetYearOne2024'          => $validated['TargetYearOne2024'] ?? null,
                'TargetYearTwo2025'          => $validated['TargetYearTwo2025'] ?? null,
                'TargetYearThree2026'        => $validated['TargetYearThree2026'] ?? null,
                'TargetYearFour2027'         => $validated['TargetYearFour2027'] ?? null,
                'TargetYearFive2028'         => $validated['TargetYearFive2028'] ?? null,
                'TargetYearSix2029'          => $validated['TargetYearSix2029'] ?? null,
                'TargetYearSeven2030'        => $validated['TargetYearSeven2030'] ?? null,
                'updated_at'                 => now(),
            ]);

        if ($affected) {
            session()->flash('success', 'RRF Indicator updated successfully!');
        } else {
            session()->flash('error', 'Failed to update RRF indicator or no changes made.');
        }

        // Re-fetch data
        return $this->refreshRRFIndicatorsView();
    }

    /**
     * Delete an RRF Indicator.
     */
    public function DeleteRRFIndicator(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:mpa_indicators,id',
        ]);

        $deleted = DB::table('mpa_indicators')
            ->where('id', $request->id)
            ->where('EntityID', 'RRF') // ensure we only delete from RRF
            ->delete();

        if ($deleted) {
            session()->flash('success', 'RRF Indicator deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete RRF indicator.');
        }

        // Return the same data
        return $this->refreshRRFIndicatorsView();
    }

    /**
     * Helper: Re-fetch all RRF indicators and return the "scrn" view with the same structure.
     */
    private function refreshRRFIndicatorsView()
    {
        // We'll create a faux "entity" object to remain consistent with your layout
        $fakeEntity = (object) [
            'EntityID' => 'RRF',
            'Entity'   => 'Regional Results Framework',
        ];

        // Grab all RRF indicators
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', 'RRF')
            ->orderBy('id', 'asc')
            ->get();

        $data = [
            "Desc"           => "Manage Regional Results Framework Indicators",
            "Page"           => "indicators.MgtRffIndicators",    // Or any partial you want
            "entities"       => DB::table('mpa_entities')->get(), // If your layout needs them
            "SelectedEntity" => $fakeEntity,                      // So Blade can display "RRF" if needed
            "indicators"     => $indicators,
        ];

        return view('scrn', $data);
    }
}