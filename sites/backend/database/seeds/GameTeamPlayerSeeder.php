<?php

use App\Models\GameTeam;
use App\Models\GameTeamPlayer;
use App\Models\Player;
use App\Services\GameSettingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class GameTeamPlayerSeeder
 */
class GameTeamPlayerSeeder extends Seeder
{
    private $gameSettingService;
    /**
     * The minimum number of players in a team in order to participate
     *
     * @var int
     */
    private $minPlayers;
    /**
     * The maximum number of players in a team in order to participate
     *
     * @var int
     */
    private $maxPlayers;

    /**
     * GameTeamPlayerSeeder constructor.
     * @param GameSettingService $gameSettingService
     */
    public function __construct(GameSettingService $gameSettingService)
    {
        $this->gameSettingService = $gameSettingService;
        $this->minPlayers = $this->gameSettingService->getMinPlayers();
        $this->maxPlayers = $this->gameSettingService->getMaxPlayers();
    }

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
        $minGuessersPerTeam = $this->minPlayers - 1;
        // -1 for the game master
        $maxGuessersPerTeam = $this->maxPlayers - 1;

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
