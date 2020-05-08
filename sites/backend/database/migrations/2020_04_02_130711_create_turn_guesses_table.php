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
            $table->id();
            $table->unsignedBigInteger('game_turn_id');
            $table->unsignedBigInteger('game_map_id')->unique();
            $table->timestamps();
            $table->softDeletes();
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
