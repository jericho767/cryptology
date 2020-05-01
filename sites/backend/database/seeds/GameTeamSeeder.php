<?php

use App\Models\Game;
use App\Models\GameTeam;
use App\Models\Player;
use App\Services\GameSettingService;
use Illuminate\Database\Seeder;

class GameTeamSeeder extends Seeder
{
    private $gameSettingService;
    /**
     * Maximum number of teams that can participate in a game
     *
     * @var int
     */
    private $maxTeams;

    public function __construct(GameSettingService $gameSettingService)
    {
        $this->gameSettingService = $gameSettingService;
        $this->maxTeams = $this->gameSettingService->getMaxTeams();
    }

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
            $numOfTeams = rand(2, $this->maxTeams);
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
