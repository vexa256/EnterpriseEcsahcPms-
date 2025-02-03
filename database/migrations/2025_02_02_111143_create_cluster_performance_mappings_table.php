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
        Schema::create('cluster_performance_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('Indicator_Name', 255);             // Name of the Performance Indicator
            $table->integer('Baseline_2023_2024')->nullable(); // Baseline Value
            $table->integer('Target_Year1')->nullable();       // Target for Year 1
            $table->integer('Target_Year2')->nullable();       // Target for Year 2
            $table->integer('Target_Year3')->nullable();       // Target for Year 3
            $table->json('Responsible_Cluster');               // Responsible Clusters/Programs/Projects
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster_performance_mappings');
    }
};