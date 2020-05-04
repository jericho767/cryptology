<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkGameTeamsGameMaster
 */
class CreateFkGameTeamsGameMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table): void {
            $table->foreign('game_master')
                ->references('id')
                ->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table): void {
            $table->dropForeign('game_teams_game_master_foreign');
        });
    }
}
