<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkGameMapsGameTeamId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_maps', function (Blueprint $table) {
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
    public function down()
    {
        Schema::table('game_maps', function (Blueprint $table) {
            $table->dropForeign('game_maps_game_team_id_foreign');
        });
    }
}
