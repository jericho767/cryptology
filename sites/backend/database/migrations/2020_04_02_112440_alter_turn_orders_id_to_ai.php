<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterTurnOrdersIdToAi
 */
class AlterTurnOrdersIdToAi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('turn_orders', function (Blueprint $table): void {
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
        Schema::table('turn_orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('id', false)->change();
        });
    }
}
