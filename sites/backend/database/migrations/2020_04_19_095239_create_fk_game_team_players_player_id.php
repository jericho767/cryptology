<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkGameTeamPlayersPlayerId
 */
class CreateFkGameTeamPlayersPlayerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_team_players', function (Blueprint $table): void {
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
        Schema::table('game_team_players', function (Blueprint $table): void {
            $table->dropForeign('game_team_players_player_id_foreign');
        });
    }
}
