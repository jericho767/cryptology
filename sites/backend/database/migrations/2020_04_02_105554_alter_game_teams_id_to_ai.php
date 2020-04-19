<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGameTeamsIdToAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('game_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->change();
        });
    }
}
