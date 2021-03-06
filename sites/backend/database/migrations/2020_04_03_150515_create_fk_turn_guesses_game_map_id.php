<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkTurnGuessesGameMapId
 */
class CreateFkTurnGuessesGameMapId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('turn_guesses', function (Blueprint $table): void {
            $table->foreign('game_map_id')
                ->references('id')
                ->on('game_maps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('turn_guesses', function (Blueprint $table): void {
            $table->dropForeign('turn_guesses_game_map_id_foreign');
        });
    }
}
