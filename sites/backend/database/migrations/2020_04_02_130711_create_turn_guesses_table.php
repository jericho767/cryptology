<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTurnGuessesTable
 */
class CreateTurnGuessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('turn_guesses', function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('game_turn_id');
            $table->unsignedBigInteger('game_map_id');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['game_turn_id', 'game_map_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('turn_guesses');
    }
}
