<?php

use App\Models\Game;
use App\Models\GameTeam;
use App\Models\Player;
use Illuminate\Database\Seeder;

class GameTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $games = Game::all();
        $playerIds = Player::all()->pluck('id')->shuffle();

        $games->each(function (Game $game) use ($playerIds): void {
            // Random number of participating teams in the game
            $numOfTeams = rand(2, Game::MAX_NUMBER_OF_PLAYING_TEAMS);
            // Get game masters for the teams
            $gameMasters = $playerIds->random($numOfTeams);

            factory(GameTeam::class, $numOfTeams)->make()->each(
                function (GameTeam $gameTeam) use ($game, $gameMasters): void {
                    $gameTeam->setAttribute('game_id', $game->getAttribute('id'));
                    $gameTeam->setAttribute('game_master', $gameMasters->pop());
                    $gameTeam->save();
            });
        });
    }
}
