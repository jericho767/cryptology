<?php

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $playerIds = Player::all()->pluck('id');

        factory(Game::class, 100)->make()->each(function (Game $game) use ($playerIds): void {
            // Save the game only if the game name does not exists
            if (!Game::query()->where('name', $game->getAttribute('name'))->exists()) {
                /*
                 * TEMPORARILY SET creator of the game.
                 * The creator must be a player coming from any of the participating teams
                 */
                $game->setAttribute('created_by', $playerIds->random());
                $game->save();
            }
        });
    }
}
