<?php

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Player::class, 80)->make()->each(function (Player $player): void {
            // Check if player with the same email address exists
            $hasExists = Player::query()
                ->where('email', $player->getAttribute('email'))
                ->exists();

            if (!$hasExists) {
                $player->save();
            }
        });
    }
}
