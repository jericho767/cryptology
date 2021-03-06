<?php

use App\Models\GameSetting;
use Illuminate\Database\Seeder;

/**
 * Class GameSettingSeeder
 */
class GameSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $gameSetting = new GameSetting([
            'map_size' => 25,
            'guess_count' => 8,
            'max_teams' => 2,
            'min_players' => 2,
            'max_players' => 6,
            'is_active' => GameSetting::IS_ACTIVE,
            'created_by' => PlayerSeeder::SUPER_ADMIN_DATA['id'],
        ]);
        $gameSetting->save();
    }
}
