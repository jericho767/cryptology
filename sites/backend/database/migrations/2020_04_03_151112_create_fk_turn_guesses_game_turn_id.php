<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkTurnGuessesGameTurnId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('turn_guesses', function (Blueprint $table): void {
            $table->foreign('game_turn_id')
                ->references('id')
                ->on('game_turns');
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
            $table->dropForeign('turn_guesses_game_turn_id_foreign');
        });
    }
}
