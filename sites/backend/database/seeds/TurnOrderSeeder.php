<?php

use App\Models\GameTeam;
use App\Models\GameTeamPlayer;
use App\TurnOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class TurnOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch game teams grouped by game
        $gameTeamsByGame = GameTeam::with(['players', 'gameBlocks'])->get()->groupBy('game_id');

        foreach ($gameTeamsByGame as $gameId => $gameTeams) {
            /** @var Collection $gameTeams */
            $gameTeams->sortByDesc(function (GameTeam $gameTeam): int {
                /*
                 * Sort game teams by number of guesses
                 * (first index to be the most number of blocks to guess)
                 */
                return $gameTeam->getRelation('gameBlocks')->count();
            })->values()->each(function (GameTeam $gameTeam): void {
                /*
                 * Fetch game team players, sort them by ID.
                 * `game_team_players`.`id` will be the basis for the turn of each player
                 */
                $gameTeam
                    ->getRelation('players')
                    ->sortBy('id')
                    ->values()
                    ->each(function (GameTeamPlayer $gameTeamPlayer): void {
                        // Add game team player to the turn sequence order
                        $turnOrder = new TurnOrder([
                            'game_team_player_id' => $gameTeamPlayer->getAttribute('id'),
                        ]);

                        // Save
                        $turnOrder->save();
                    });
            });
        }
    }
}
