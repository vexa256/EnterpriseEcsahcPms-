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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('EntityID', 255)->nullable();
            $table->string('ClusterID', 255)->nullable();
            $table->enum('UserTyoe', ['MPA', 'ECSA-HC'])->default('ECSA-HC');
            $table->string('UserCode', 255)->unique()->nullable();
            $table->string('Phone', 20)->nullable();
            $table->string('Nationality', 100)->nullable();
            $table->string('PhoneNumber', 20)->nullable();
            $table->text('Address')->nullable();
            $table->string('ParentOrganization', 255)->nullable();
            $table->enum('Sex', ['Male', 'Female'])->nullable();
            $table->string('JobTitle', 255)->nullable();
            $table->enum('AccountRole', ['Admin', 'User', 'Viewer'])->default('User');
            $table->string('UserID', 255)->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};