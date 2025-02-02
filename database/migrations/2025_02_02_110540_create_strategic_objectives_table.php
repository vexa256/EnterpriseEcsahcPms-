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
        Schema::create('strategic_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('SO_Number', 255);            // Name of the Strategic Objective
            $table->string('StrategicObjectiveID', 255); // Name of the Strategic Objective
            $table->string('SO_Name', 255);              // Name of the Strategic Objective
            $table->text('Description')->nullable();     // Optional Description
            $table->timestamps();                        // Created at and Updated at timestamps
                                                         // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategic_objectives');
    }
};