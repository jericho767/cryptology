<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_maps', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('word_id');
            $table->unsignedSmallInteger('block_number');
            $table->unsignedSmallInteger('block_owner');
            $table->unsignedBigInteger('game_team_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['game_id', 'word_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_maps');
    }
}
