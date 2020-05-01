<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_settings', function (Blueprint $table): void {
            $table->id();
            $table->smallInteger('map_size');
            $table->smallInteger('guess_count');
            $table->smallInteger('max_teams');
            $table->smallInteger('min_players');
            $table->smallInteger('max_players');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
}