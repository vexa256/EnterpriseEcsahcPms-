<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MpaRRFController extends Controller
{
    /**
     * Display all Regional Results Framework (RRF) indicators.
     */
    public function ShowRRFIndicators(Request $request)
    {
        // Fetch all indicators with EntityID = 'RRF'
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', 'RRF')
            ->orderBy('id', 'asc')
            ->get();

        // Create a fake entity object so that the Blade view can display details.
        $fakeEntity = (object) [
            'EntityID' => 'RRF',
            'Entity'   => 'Regional Results Framework',
        ];

        $data = [
                                                                  // "Desc"           => "Manage Regional Results Framework Indicators",
            "Page"           => "indicators.MgtRffIndicators",    // This is the Blade partial that displays the list.
            "entities"       => DB::table('mpa_entities')->get(), // All entities, if needed by layout.
            "SelectedEntity" => $fakeEntity,
            "indicators"     => $indicators,
        ];

        return view('scrn', $data);
    }

    /**
     * Store a new RRF Indicator.
     */
    public function StoreRRFIndicator(Request $request)
    {
        // Validate the incoming form data.
        // Note: The form fields are named "IndicatorPrimaryCategory" and "IndicatorSecondaryCategory"
        // but we map these to our database columns "PrimaryCategory" and "SecondaryCategory".
        $validated = $request->validate([
            'EntityID'                   => 'required|exists:mpa_entities,EntityID', // This field may not be sent; we force it below.
            'IndicatorPrimaryCategory'   => 'required|string|max:255',
            'IndicatorSecondaryCategory' => 'required|string|max:255',
            'IID'                        => 'required|string|max:255|unique:mpa_indicators,IID',
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Yes/No,Percentage',
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

        // Insert the new indicator record; we force EntityID to 'RRF'
        $insertedId = DB::table('mpa_indicators')->insertGetId([
            'EntityID'            => 'RRF',
            'PrimaryCategory'     => $validated['IndicatorPrimaryCategory'],   // Mapped to db column
            'SecondaryCategory'   => $validated['IndicatorSecondaryCategory'], // Mapped to db column
            'IID'                 => $validated['IID'],
            'Indicator'           => $validated['Indicator'],
            'IndicatorDefinition' => $validated['IndicatorDefinition'] ?? null,
            'IndicatorQuestion'   => $validated['IndicatorQuestion'] ?? null,
            'RemarksComments'     => $validated['RemarksComments'] ?? null,
            'SourceOfData'        => $validated['SourceOfData'] ?? null,
            'ResponseType'        => $validated['ResponseType'],
            'ReportingPeriod'     => $validated['ReportingPeriod'] ?? null,
            'ExpectedTarget'      => $validated['ExpectedTarget'] ?? null,
            'BaselinePAD2023'     => $validated['BaselinePAD2023'] ?? null,
            'Baseline2024'        => $validated['Baseline2024'] ?? null,
            'TargetYearOne2024'   => $validated['TargetYearOne2024'] ?? null,
            'TargetYearTwo2025'   => $validated['TargetYearTwo2025'] ?? null,
            'TargetYearThree2026' => $validated['TargetYearThree2026'] ?? null,
            'TargetYearFour2027'  => $validated['TargetYearFour2027'] ?? null,
            'TargetYearFive2028'  => $validated['TargetYearFive2028'] ?? null,
            'TargetYearSix2029'   => $validated['TargetYearSix2029'] ?? null,
            'TargetYearSeven2030' => $validated['TargetYearSeven2030'] ?? null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        if ($insertedId) {
            session()->flash('success', 'RRF Indicator added successfully!');
        } else {
            session()->flash('error', 'Failed to add RRF indicator. Please try again.');
        }

        // Refresh and return the updated view.
        return $this->refreshRRFIndicatorsView();
    }

    /**
     * Update an existing RRF Indicator.
     */
    public function UpdateRRFIndicator(Request $request)
    {
        $validated = $request->validate([
            'id'                         => 'required|exists:mpa_indicators,id',
            // 'EntityID'                   => 'required|exists:mpa_entities,EntityID',
            'IndicatorPrimaryCategory'   => 'required|string|max:255',
            'IndicatorSecondaryCategory' => 'required|string|max:255',
            'IID'                        => 'required|string|max:255|unique:mpa_indicators,IID,' . $request->id,
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Yes/No,Percentage',
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
            ->where('id', $request->id)
            ->where('EntityID', 'RRF') // Ensure we update only RRF indicators
            ->update([
                'PrimaryCategory'     => $validated['IndicatorPrimaryCategory'],
                'SecondaryCategory'   => $validated['IndicatorSecondaryCategory'],
                'IID'                 => $validated['IID'],
                'Indicator'           => $validated['Indicator'],
                'IndicatorDefinition' => $validated['IndicatorDefinition'] ?? null,
                'IndicatorQuestion'   => $validated['IndicatorQuestion'] ?? null,
                'RemarksComments'     => $validated['RemarksComments'] ?? null,
                'SourceOfData'        => $validated['SourceOfData'] ?? null,
                'ResponseType'        => $validated['ResponseType'],
                'ReportingPeriod'     => $validated['ReportingPeriod'] ?? null,
                'ExpectedTarget'      => $validated['ExpectedTarget'] ?? null,
                'BaselinePAD2023'     => $validated['BaselinePAD2023'] ?? null,
                'Baseline2024'        => $validated['Baseline2024'] ?? null,
                'TargetYearOne2024'   => $validated['TargetYearOne2024'] ?? null,
                'TargetYearTwo2025'   => $validated['TargetYearTwo2025'] ?? null,
                'TargetYearThree2026' => $validated['TargetYearThree2026'] ?? null,
                'TargetYearFour2027'  => $validated['TargetYearFour2027'] ?? null,
                'TargetYearFive2028'  => $validated['TargetYearFive2028'] ?? null,
                'TargetYearSix2029'   => $validated['TargetYearSix2029'] ?? null,
                'TargetYearSeven2030' => $validated['TargetYearSeven2030'] ?? null,
                'updated_at'          => now(),
            ]);

        if ($affected) {
            session()->flash('success', 'RRF Indicator updated successfully!');
        } else {
            session()->flash('error', 'Failed to update RRF indicator or no changes made.');
        }

        return $this->refreshRRFIndicatorsView();
    }

    /**
     * Helper function to refresh the RRF indicators view.
     */
    protected function refreshRRFIndicatorsView()
    {
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', 'RRF')
            ->orderBy('id', 'asc')
            ->get();

        $fakeEntity = (object) [
            'EntityID' => 'RRF',
            'Entity'   => 'Regional Results Framework',
        ];

        $data = [
            // "Desc"           => "Manage Regional Results Framework Indicators",
            "Page"           => "indicators.MgtRffIndicators",
            "entities"       => DB::table('mpa_entities')->get(),
            "SelectedEntity" => $fakeEntity,
            "indicators"     => $indicators,
        ];

        return view('scrn', $data);
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

}