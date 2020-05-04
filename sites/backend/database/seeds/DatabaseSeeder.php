<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(WordSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(PlayerActivitySeeder::class);
        $this->call(GameSeeder::class);
        $this->call(GameTeamSeeder::class);
        $this->call(GameMapSeeder::class);
        $this->call(GameTeamPlayerSeeder::class);
        $this->call(TurnOrderSeeder::class);
        $this->call(GameSettingSeeder::class);
    }
}
