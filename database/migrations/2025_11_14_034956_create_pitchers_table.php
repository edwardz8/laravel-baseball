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
        Schema::create('pitchers', function (Blueprint $table) {
            $table->integer('rank')->primary();
            $table->string('player');
            $table->integer('age');
            $table->string('team')->nullable();
            $table->float('war')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitchers');
    }
};



/* 
'rank' => (int) trim($cells->eq(0)->text()),
'playee' => trim($cells->eq(1)->text()), // e.g., "Logan Webb"
'age' => (int) trim($cells->eq(2)->text()),
'team' => trim($cells->eq(3)->text() ?? ''), // Team abbr
'league' => trim($cells->eq(4)->text() ?? ''), // League
'war' => trim($cells->eq(5)->text() ?? ''), // War
'wins' => trim($cells->eq(6)->text() ?? ''), // Wins
'losses' => trim($cells->eq(7)->text() ?? ''), // Losses
'era' => trim($cells->eq(9)->text() ?? ''), // ERA
'games' => trim($cells->eq(10)->text() ?? ''), // Games
'games_started' => trim($cells->eq(11)->text() ?? ''), // Games Started
'saves' => trim($cells->eq(15)->text() ?? ''), // Saves
'innings_pitched' => trim($cells->eq(16)->text() ?? ''), // Innings Pitched
'strikeouts' => trim($cells->eq(23)->text() ?? ''), // Strikeouts
'era_plus' => trim($cells->eq(28)->text() ?? ''), // ERA+
'fip' => trim($cells->eq(29)->text() ?? ''), // FIP
'whip' => trim($cells->eq(30)->text() ?? ''), // WHIP
*/