<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameTeamPlayersTable
 */
class CreateGameTeamPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_team_players', function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('game_team_id');
            $table->unsignedBigInteger('player_id');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['game_team_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('game_team_players');
    }
}
