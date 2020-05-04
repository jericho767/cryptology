<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTurnOrdersTable
 */
class CreateTurnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('turn_orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('game_team_player_id');
            $table->unsignedTinyInteger('has_played')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->primary('game_team_player_id');
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
