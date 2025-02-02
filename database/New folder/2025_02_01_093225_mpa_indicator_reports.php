<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpaIndicatorAndReportsTable extends Migration
{
    public function up()
    {
        Schema::create('mpa_indicator_reports', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('RID', 255);
            $table->string('IID', 255);
            $table->string('EntityID', 255);
            $table->string('ReportingID', 255);
            $table->string('ReportedBy', 255);
            $table->text('Response')->nullable();
            $table->text('Comments')->nullable();
            $table->string('IndicatorScore', 50)->nullable();
            $table->enum('ApprovalStatus', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('ResponseType', ['Text', 'Number', 'Boolean']);
            $table->text('AnsweredQuestion')->nullable();
            $table->foreign('EntityID')->references('EntityID')->on('mpa_entities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mpa_indicator_reports');
    }
}