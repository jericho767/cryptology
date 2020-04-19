<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkGameTurnsTurnOrderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_turns', function (Blueprint $table): void {
            $table->foreign('turn_order_id')
                ->references('id')
                ->on('turn_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('game_turns', function (Blueprint $table): void {
            $table->dropForeign('game_turns_turn_order_id_foreign');
        });
    }
}
