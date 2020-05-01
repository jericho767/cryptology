<?php

use App\Models\Game;
use App\Models\GameTeam;
use App\GameTeamPlayer;
use App\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class GameTeamPlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $gameTeamsByGame = GameTeam::all()->groupBy('game_id');
        $players = Player::all();
        // -1 for the game master
        $minGuessersPerTeam = Game::MIN_PLAYERS_PER_TEAM - 1;
        // -1 for the game master
        $maxGuessersPerTeam = Game::MAX_PLAYERS_PER_TEAM - 1;

        foreach ($gameTeamsByGame as $gameId => $gameTeams) {
            /** @var Collection $gameTeams */
            // Remove the game masters from the poll of possible guessers
            $possiblePlayers = $players->whereNotIn(
                'id',
                $gameTeams->pluck('game_master')
            )->shuffle();

            foreach ($gameTeams as $gameTeam) {
                /** @var GameTeam $gameTeam */
                // Fetch our guessers
                $guessers = $possiblePlayers->splice(0, rand($minGuessersPerTeam, $maxGuessersPerTeam));
                foreach ($guessers as $guesser) {
                    /** @var Player $guesser */
                    $gameTeamPlayer = new GameTeamPlayer([
                        'game_team_id' => $gameTeam->getAttribute('id'),
                        'player_id' => $guesser->getAttribute('id'),
                    ]);

                    // Save that game team player
                    $gameTeamPlayer->save();
                }
            }
        }
    }
}
