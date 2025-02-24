<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MpaIndicatorsController extends Controller
{
    /**
     * 1) Show a form to select an entity from mpa_entities.
     *    Data must be returned in the common format for the "scrn" view.
     */
    public function SelectEntity()
    {
        $data = [
            "Desc"     => "Select an Entity to Manage MPA Indicators Attcahed to It",
            "Page"     => "indicators.SelectEntity", // The Blade partial to include in scrn
            "entities" => DB::table("mpa_entities")->get(),
        ];

        return view('scrn', $data);
    }

    /**
     * 2) Show all indicators belonging to the chosen Entity.
     *    The user picks an EntityID from the "SelectEntity" form.
     */
    public function ShowEntityIndicators(Request $request)
    {
        // Retrieve the EntityID from the request
        $entityID = $request->input('EntityID');

        // Verify that the specified entity exists in the mpa_entities table.
        $entity = DB::table('mpa_entities')
            ->where('EntityID', $entityID)
            ->first();

        if (! $entity) {
            // If no matching entity is found, flash an error and return to the entity selection view.
            session()->flash('error', 'The selected entity does not exist.');
            $data = [
                "Desc"     => "Select an Entity to Manage MPA Indicators",
                "Page"     => "indicators.SelectEntity", // Blade partial for entity selection
                "entities" => DB::table("mpa_entities")->get(),
            ];
            return view('scrn', $data);
        }

        // Fetch all indicators for the selected entity from the mpa_indicators table.
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', $entityID)
            ->orderBy('id', 'asc')
            ->get();

        // dd($entityID);

        // Prepare the data array to be passed to the view.
        $data = [
            "Desc"           => "Manage CRF Indicators for " . $entity->Entity,
            "Page"           => "indicators.MgtMpaIndicators",
            "entities"       => DB::table("mpa_entities")->get(), // All entities (if needed for layout)
            "SelectedEntity" => $entity,
            "indicators"     => $indicators,
            "entityID"       => $entityID,
        ];

        // Return the view with the data.
        return view('scrn', $data);
    }

    /**
     * 3a) Create (Store) a new indicator for a particular Entity.
     *     Instead of redirecting, we re-fetch data and return the "ShowEntityIndicators" view
     *     so we maintain the "scrn" format.
     */
    public function StoreIndicator(Request $request)
    {

        // dd($request->EntityID);
        // Validate the incoming form data.
        // Note: Although the form fields are named IndicatorPrimaryCategory and IndicatorSecondaryCategory,
        // we will map these values to the DB columns PrimaryCategory and SecondaryCategory.
        $validated = $request->validate([
            'EntityID'                   => 'required|exists:mpa_entities,EntityID',
            'IndicatorPrimaryCategory'   => 'required|string|in:CRF,RRF',
            'IndicatorSecondaryCategory' => 'required|string|in:"CRF PDO","CRF Intermediate","RRF PDO","RRF Intermediate"',
            'IID'                        => 'required|string',
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Percentage,Yes/No',
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

        // Insert the record into the database.
        // Map the form field names to the corresponding DB columns.
        $insertedId = DB::table('mpa_indicators')->insertGetId([
            'EntityID'            => $validated['EntityID'],
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
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        if ($insertedId) {
            session()->flash('success', 'Indicator added successfully!');
        } else {
            session()->flash('error', 'Failed to add indicator. Please try again.');
        }

        // Re-fetch and return the updated view.
        return $this->refreshEntityIndicatorsView($validated['EntityID']);
    }

    public function UpdateIndicator(Request $request)
    {
        $validated = $request->validate([
            'id'                         => 'required|exists:mpa_indicators,id',
            'EntityID'                   => 'required|exists:mpa_entities,EntityID',
            'IndicatorPrimaryCategory'   => 'required|string|in:CRF,RRF',
            'IndicatorSecondaryCategory' => 'required|string|in:"CRF PDO","CRF Intermediate","RRF PDO","RRF Intermediate"',
            'IID'                        => 'required|string|max:255|unique:mpa_indicators,IID,' . $request->id,
            'Indicator'                  => 'required|string|max:255',
            'IndicatorDefinition'        => 'nullable|string',
            'IndicatorQuestion'          => 'nullable|string',
            'RemarksComments'            => 'nullable|string',
            'SourceOfData'               => 'nullable|string|max:255',
            'ResponseType'               => 'required|in:Text,Number,Boolean,Percentage,Yes/No',
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
            ->update([
                'EntityID'            => $validated['EntityID'],
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
            session()->flash('success', 'Indicator updated successfully!');
        } else {
            session()->flash('error', 'Failed to update indicator or no changes made.');
        }

        return $this->refreshEntityIndicatorsView($validated['EntityID']);
    }

    /**
     * 3c) Delete an Indicator.
     *     Return to the same ShowEntityIndicators view with updated data.
     */
    public function DeleteIndicator(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:mpa_indicators,id',
        ]);

        // We'll need the EntityID to re-fetch the correct list. So let's quickly get it.
        $indicator = DB::table('mpa_indicators')
            ->where('id', $request->id)
            ->first();

        if (! $indicator) {
            session()->flash('error', 'Indicator not found.');
            return $this->SelectEntity(); // Fallback: show entity select if something goes wrong
        }

        $deleted = DB::table('mpa_indicators')
            ->where('id', $request->id)
            ->delete();

        if ($deleted) {
            session()->flash('success', 'Indicator deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete indicator.');
        }

        // Return the same data for the chosen entity
        return $this->refreshEntityIndicatorsView($indicator->EntityID);
    }

    /**
     * Helper: Re-fetch the same data as ShowEntityIndicators for the specified $entityID,
     *         then return the standard "scrn" view with the same structure.
     */
    private function refreshEntityIndicatorsView($entityID)
    {
        // Re-query the entity
        $entity = DB::table('mpa_entities')
            ->where('EntityID', $entityID)
            ->first();

        // If not found, fallback to entity selection
        if (! $entity) {
            session()->flash('error', 'Entity not found while refreshing after action.');
            return $this->SelectEntity();
        }

        // Re-fetch indicators for that entity
        $indicators = DB::table('mpa_indicators')
            ->where('EntityID', $entityID)
            ->orderBy('id', 'asc')
            ->get();

        // Return the consistent data array for the "scrn" view
        $data = [
            "Desc"           => "",
            "Page"           => "indicators.MgtMpaIndicators",
            "entities"       => DB::table("mpa_entities")->get(),
            "SelectedEntity" => $entity,
            "indicators"     => $indicators,
            "entityID"       => $entityID,
        ];

        return view('scrn', $data);
    }
}