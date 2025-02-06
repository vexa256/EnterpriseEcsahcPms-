<?php
namespace App\Http\Controllers;

use DB;

class EcsahcTimelines extends Controller
{
    public function MgtEcsaTimelines()
    {
        $data = [

            "Desc"      => "Manage all ECSA-HC reporting Timelines",
            "Page"      => "timelines.MgtEcsaTimelines",
            "timelines" => DB::table("ecsahc_timelines")->get(),

        ];

        return view('scrn', $data);
    }

    public function MgtEcsaTimelinesStatus()
    {
        $data = [

            "Desc"      => "Activate/Disable ECSA-HC Reporting Timelines",
            "Page"      => "timelines.EcsaTimelineStatus",
            "timelines" => DB::table("ecsahc_timelines")->get(),

        ];

        return view('scrn', $data);
    }

    public function MgtMpaTimelines()
    {
        $data = [

            "Desc"      => "Manage all MPA reporting Timelines",
            "Page"      => "timelines.MgtMpaTimelines",
            "timelines" => DB::table("mpa_timelines")->get(),

        ];

        return view('scrn', $data);
    }

    public function MgtMpaTimelinesStatus()
    {
        $data = [

            "Desc"      => "Activate/Disable MPA Reporting Timelines",
            "Page"      => "timelines.MpaTimelineStatus",
            "timelines" => DB::table("mpa_timelines")->get(),

        ];

        return view('scrn', $data);
    }

}