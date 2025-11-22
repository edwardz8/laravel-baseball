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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique(); // API's player ID
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('position')->nullable();
            $table->boolean('active')->default(false);
            $table->string('jersey')->nullable();
            $table->date('dob')->nullable(); // Date of birth
            $table->integer('age')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('bats_throws')->nullable();
            $table->unsignedBigInteger('team_id')->nullable(); // Reference to team
            $table->string('team_name')->nullable(); // Denormalized for simplicity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
