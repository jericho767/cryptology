<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGameTeamPlayersIdToAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_team_players', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_team_players', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->change();
        });
    }
}