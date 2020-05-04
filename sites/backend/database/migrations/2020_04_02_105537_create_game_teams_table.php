<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameTeamsTable
 */
class CreateGameTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_teams', function (Blueprint $table): void {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('game_id');
            $table->string('team_name');
            $table->unsignedBigInteger('game_master');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['game_id', 'game_master']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('game_teams');
    }
}
