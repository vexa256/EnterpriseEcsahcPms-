<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpaProjectIndicatorsTable extends Migration
{
    public function up()
    {
        Schema::create('mpa_project_indicators', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('IndicatorPrimaryCategory', 255);
            $table->string('IndicatorSecondaryCategory', 255);
            $table->string('EntityID', 255);
            $table->string('IID', 255);
            $table->string('Indicator', 255);
            $table->text('IndicatorDefinition')->nullable();
            $table->text('IndicatorQuestion')->nullable();
            $table->text('RemarksComments')->nullable();
            $table->string('SourceOfData', 255)->nullable();
            $table->enum('ResponseType', ['Text', 'Number', 'Boolean', 'Percentage', 'Yes/No']);
            $table->string('ReportingPeriod', 50)->nullable();
            $table->string('ExpectedTarget', 255)->nullable();
            $table->string('BaselinePAD2023', 255)->nullable();
            $table->string('Baseline2024', 255)->nullable();
            $table->string('TargetYearOne2024', 255)->nullable();
            $table->string('TargetYearTwo2025', 255)->nullable();
            $table->string('TargetYearThree2026', 255)->nullable();
            $table->string('TargetYearFour2027', 255)->nullable();
            $table->string('TargetYearFive2028', 255)->nullable();
            $table->string('TargetYearSix2029', 255)->nullable();
            $table->string('TargetYearSeven2030', 255)->nullable();
            $table->foreign('EntityID')->references('EntityID')->on('mpa_entities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mpa_project_indicators');
    }
}