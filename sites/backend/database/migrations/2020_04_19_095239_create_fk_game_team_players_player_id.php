<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkGameTeamPlayersPlayerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_team_players', function (Blueprint $table) {
            $table->foreign('player_id')
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
        Schema::table('game_team_players', function (Blueprint $table) {
            $table->dropForeign('game_team_players_player_id_foreign');
        });
    }
}
