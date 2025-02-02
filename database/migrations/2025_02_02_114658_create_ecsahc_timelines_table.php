<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ecsahc_timelines', function (Blueprint $table) {
            $table->id();
            // $table->id();
            // $table->string('id', 50)->primary();
            $table->string('ReportName', 255);
            $table->string('Type', 255);
            $table->text('Description')->nullable();
            $table->string('ReportingID', 255);
            $table->string('Year', 4);
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->boolean('LastBiAnnual')->default(false);
            $table->index(['ReportName', 'ReportingID'], 'idx_report_name');
            $table->unique(['ReportName', 'ReportingID'], 'idx_report_name_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecsahc_timelines');
    }
};