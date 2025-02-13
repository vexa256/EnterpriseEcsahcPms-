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
        Schema::create('mpa_reports', function (Blueprint $table) {
            // $table->string('id', 50)->primary();
            $table->id();
            $table->string('RID', 255);      // Report ID unique
            $table->string('IID', 255);      // Reported Indicator ID
            $table->string('EntityID', 255); //EntityID
            $table->string('PrimaryCategory', 255);
            $table->string('SecondaryCategory', 255);
            $table->string('ReportingID', 255);   //Reporting Timeline ID
            $table->string('ReportedBy', 255);    // Reported By
            $table->text('Response')->nullable(); //Recorded Indicator Response to indicator
            $table->text('Comments')->nullable(); //Reporters Comments
            $table->enum('ApprovalStatus', ['Pending', 'Approved', 'Rejected'])->default('Approved');
            $table->enum('ResponseType', ['Text', 'Number', 'Boolean', 'Yes/No', 'Percentage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpa_reports');
    }
};