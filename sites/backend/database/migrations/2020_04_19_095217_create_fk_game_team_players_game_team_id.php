<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkGameTeamPlayersGameTeamId
 */
class CreateFkGameTeamPlayersGameTeamId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_team_players', function (Blueprint $table): void {
            $table->foreign('game_team_id')
                ->references('id')
                ->on('game_teams');
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
            $table->dropForeign('game_team_players_game_team_id_foreign');
        });
    }
}
