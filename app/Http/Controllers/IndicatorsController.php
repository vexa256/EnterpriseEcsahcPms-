<?php
namespace App\Http\Controllers;

use DB;

class IndicatorsController extends Controller
{
    public function MgtSO()
    {
        $data = [

            "Desc"                => "Manage ECSA-HC Strategic Ojectives",
            "Page"                => "indicators.MgtSO",
            "strategicObjectives" => DB::table("strategic_objectives")->get(),

        ];

        return view('scrn', $data);
    }



    
}