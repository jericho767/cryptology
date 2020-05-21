<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkGameSettingsCreatedBy
 */
class CreateFkGameSettingsCreatedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('game_settings', function (Blueprint $table): void {
            $table->foreign('created_by')
                ->references('id')
                ->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('game_settings', function (Blueprint $table): void {
            $table->dropForeign('game_settings_created_by_foreign');
        });
    }
}
