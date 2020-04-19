<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkTurnOrdersGameTeamPlayerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('turn_orders', function (Blueprint $table): void {
            $table->foreign('game_team_player_id')
                ->references('id')
                ->on('game_team_players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('turn_orders', function (Blueprint $table): void {
            $table->dropForeign('turn_orders_game_team_player_id_foreign');
        });
    }
}
