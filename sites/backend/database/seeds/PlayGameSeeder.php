<?php

use App\Models\Game;
use App\Services\GameSimulatorService;
use Illuminate\Database\Seeder;

/**
 * Seeder that will play all seeded games.
 *
 * Class PlayGameSeeder
 */
class PlayGameSeeder extends Seeder
{
    private $gameSimulatorService;

    /**
     * PlayGameSeeder constructor.
     * @param GameSimulatorService $gsService
     */
    public function __construct(GameSimulatorService $gsService)
    {
        $this->gameSimulatorService = $gsService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all games together with the map and its participating teams
        $games = Game::all();

        $games->each(function (Game $game): void {
            $this->gameSimulatorService->simulate($game->getAttribute('id'));
        });
    }
}
