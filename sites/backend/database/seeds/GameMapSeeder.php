<?php

use App\Models\Game;
use App\Models\GameMap;
use App\Models\Word;
use App\Services\GameService;
use App\Services\GameSettingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class GameMapSeeder
 */
class GameMapSeeder extends Seeder
{
    private $gameSettingsService;
    private $gameService;
    /**
     * Current setting for the size of the map
     *
     * @var int
     */
    private $mapSize;
    /**
     * Current setting for the count of guesses
     *
     * @var int
     */
    private $guessSize;

    /**
     * GameMapSeeder constructor.
     * @param GameSettingService $gameSettings
     * @param GameService $gameService
     */
    public function __construct(GameSettingService $gameSettings, GameService $gameService)
    {
        $this->gameSettingsService = $gameSettings;
        $this->gameService = $gameService;
        $this->mapSize = $this->gameSettingsService->getMapSize();
        $this->guessSize = $this->gameSettingsService->getGuessCount();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all games
        $games = Game::with(['participants'])->get();

        $games->each(function (Game $game): void {
            // Number of participating teams
            $participantCount = $game->getRelation('participants')->count();

            // This holds all the blocks in the game
            $map = $this->initMap($game->getAttribute('id'));

            // Assigns words to each of the blocks
            $this->setWordsToBlocks($map);

            // Fetch the block numbers
            $mapNums = $map->pluck('block_number')->shuffle();

            // Blocks for assassins
            $assassinBlocks = $this->getAssassinBlocks($mapNums, $participantCount);

            // Mapping of blocks with the assigned team ID
            $teamMap = $this->getTeamMap(
                $mapNums,
                $game->getRelation('participants')
            );

            $this->setOwnersToBlocks($map, $assassinBlocks, $teamMap);
        });
    }

    /**
     * Sets the owners of the map blocks
     *
     * @param Collection $map
     * @param Collection $assassinBlocks
     * @param Collection $teamMap
     */
    private function setOwnersToBlocks(Collection $map, Collection $assassinBlocks, Collection $teamMap): void
    {
        // Gets the blocks assigned to teams
        $teamBlocks = $teamMap->keys();

        $callback = function (GameMap $gameMap, int $blockNum) use ($assassinBlocks, $teamMap, $teamBlocks): GameMap {
            if ($teamBlocks->containsStrict($blockNum)) {
                // Block is for the team so assign attributes accordingly
                $gameMap->setAttribute('block_owner', GameMap::TEAM_BLOCK_NUM);
                $gameMap->setAttribute('game_team_id', $teamMap->get($blockNum));
            } elseif ($assassinBlocks->containsStrict($blockNum)) {
                // Block is for an assassin
                $gameMap->setAttribute('block_owner', GameMap::ASSASSIN_BLOCK_NUM);
            } else {
                // Block is for a civilian
                $gameMap->setAttribute('block_owner', GameMap::CIVILIAN_BLOCK_NUM);
            }

            $gameMap->save();
            return $gameMap;
        };

        $map->transform($callback);
    }

    /**
     * Set words to every blocks in the map
     *
     * @param Collection $map
     */
    private function setWordsToBlocks(Collection $map): void
    {
        // Fetch all words
        $words = Word::all()->pluck('id')->shuffle();

        $map->transform(function (GameMap $gameMap) use ($words) {
            $gameMap->setAttribute('word_id', $words->pop());
            return $gameMap;
        });
    }

    /**
     * Gets the blocks that will be assigned to assassins
     *
     * @param Collection $blockNumbers
     * @param int $participantCount
     * @return Collection
     */
    private function getAssassinBlocks(Collection $blockNumbers, int $participantCount): Collection
    {
        return $blockNumbers->splice(0, $this->gameService->getNumOfAssassins($participantCount));
    }

    /**
     * Gets the blocks that will be assigned to the teams
     *
     * @param Collection $blockNumbers
     * @param Collection $participants
     * @return Collection
     */
    private function getTeamMap(Collection $blockNumbers, Collection $participants): Collection
    {
        // Team ID of the participants
        $teamIds = $participants->pluck('id')->shuffle();

        // Team IDs array to be matched/assigned to team blocks
        $teamIdsForBlocks = collect([]);

        foreach ($teamIds as $adtlGuesses => $teamId) {
            /*
             * The index determines the additional guesses
             * Which means the last team listed will have the most guesses needed
             * and therefore be the first team to take the turn in the game
             */
            for ($i = 0; $i < $this->guessSize + $adtlGuesses; $i++) {
                $teamIdsForBlocks->push($teamId);
            }
        }

        $teamBlocks = $blockNumbers->splice(0, $teamIdsForBlocks->count());

        // Assign a team ID to each of the team block
        return $teamBlocks->combine($teamIdsForBlocks);
    }

    /**
     * Initialize blocks by assigning block numbers and the game ID
     *
     * @param int $gameId
     * @return Collection
     */
    private function initMap(int $gameId): Collection
    {
        $map = collect([]);

        for ($mapNum = 1; $mapNum <= $this->mapSize; $mapNum++) {
            $map->put(
                $mapNum,
                new GameMap([
                    'block_number' => $mapNum,
                    'game_id' => $gameId,
                ])
            );
        }

        return $map;
    }
}
