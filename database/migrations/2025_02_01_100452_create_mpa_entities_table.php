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
        Schema::create('mpa_entities', function (Blueprint $table) {
            $table->id();
            // $table->string('id', 50)->primary();
            $table->string('Entity', 255);
            $table->string('EntityID', 255)->unique();
            $table->text('EntityProjectDetails')->nullable();
            $table->index(['Entity', 'EntityID'], 'idx_entity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpa_entities');
    }
};