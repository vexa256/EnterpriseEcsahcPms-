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
            $table->string('RID', 255);
            $table->string('IID', 255);
            $table->string('EntityID', 255);
            $table->string('ReportingID', 255);
            $table->string('ReportedBy', 255);
            $table->text('Response')->nullable();
            $table->text('Comments')->nullable();
            $table->string('IndicatorScore', 50)->nullable();
            $table->enum('ApprovalStatus', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('ResponseType', ['Text', 'Number', 'Boolean', 'Yes/No', 'Percentage']);
            $table->text('AnsweredQuestion')->nullable();
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