<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFkWordsCreatedBy
 */
class CreateFkWordsCreatedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('words', function (Blueprint $table): void {
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
        Schema::table('words', function (Blueprint $table): void {
            $table->dropForeign('words_created_by_foreign');
        });
    }
}
