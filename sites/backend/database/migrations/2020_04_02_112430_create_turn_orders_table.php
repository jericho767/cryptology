<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('turn_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->unique();
            $table->unsignedBigInteger('game_team_player_id')->primary();
            $table->unsignedTinyInteger('has_played')->default(0);
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
        Schema::dropIfExists('turn_orders');
    }
}
