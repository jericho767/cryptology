<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkGameMapsWordId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_maps', function (Blueprint $table) {
            $table->foreign('word_id')
                ->references('id')
                ->on('words');
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
            $table->dropForeign('game_maps_word_id_foreign');
        });
    }
}
