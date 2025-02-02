<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpaReportingTimelinesTable extends Migration
{
    public function up()
    {
        Schema::create('mpa_reporting_timelines', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('ReportName', 255);
            $table->string('Type', 255);
            $table->text('Description')->nullable();
            $table->string('ReportingID', 255);
            $table->string('Year', 4);
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->boolean('LastBiAnnual')->default(false);
            $table->index(['ReportName', 'ReportingID'], 'idx_report_name');
            $table->unique(['ReportName', 'ReportingID'], 'idx_report_name_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mpa_reporting_timelines');
    }
}