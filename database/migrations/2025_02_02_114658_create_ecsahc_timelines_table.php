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

            $table->string('ReportName', 255);
            $table->enum('Type', ['Quarterly Reports', 'Annual Reports', 'Bi-Annual Reports']);
            $table->text('Description')->nullable();
            $table->string('ReportingID', 255);
            $table->integer('Year');
            $table->date('ClosingDate');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->enum('LastBiAnnual', ['Yes', 'No']);

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