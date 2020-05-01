<?php

use App\Models\Game;
use App\Models\GameMap;
use App\Models\Word;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class GameMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all games
        $games = Game::with(['participants'])->get();

        // Fetch all words
        $words = Word::all()->pluck('id')->shuffle();

        $games->each(function (Game $game) use ($words): void {
            // This holds all the blocks in the game
            $map = collect([]);

            // Randomly fetch words for the blocks from the poll
            $mapWords = $words->random(GameMap::MAP_SIZE)->shuffle();

            // Initialize all blocks model
            for ($mapNum = 1; $mapNum <= GameMap::MAP_SIZE; $mapNum++) {
                // Add block to the blocks list
                $map->put(
                    $mapNum,
                    new GameMap([
                        'game_id' => $game->getAttribute('id'),
                        'word_id' => $mapWords->pop(),
                        'block_number' => $mapNum,
                    ])
                );
            }

            // Block numbers
            $mapNums = $map->pluck('block_number')->shuffle();

            // Blocks for assassins
            /** @var Collection $assassinMap */
            $assassinMap = $mapNums->splice(0, GameMap::NUM_OF_ASSASSIN);

            // Create team id array to be assigned to every team block
            $teamIds = $game->getRelation('participants')->pluck('id')->shuffle();
            $teamIdForMap = collect([]);

            foreach ($teamIds as $i => $teamId) {
                if ($i === 0) {
                    // Make this team as the first team to make a guess
                    for ($i = 0; $i < GameMap::GUESS_SIZE + GameMap::FIRST_TURN_ADD; $i++) {
                        $teamIdForMap->push($teamId);
                    }
                } else {
                    // Succeeding teams
                    for ($i = 0; $i < GameMap::GUESS_SIZE; $i++) {
                        $teamIdForMap->push($teamId);
                    }
                }
            }

            // Blocks for teams
            /** @var Collection $teamMaps */
            $teamMaps = $mapNums->splice(0, $teamIdForMap->count());
            $teamIdForMap = $teamMaps->combine($teamIdForMap);

            // Assign owners for all the blocks
            foreach ($map as $blockNum => $block) {
                if ($teamMaps->containsStrict($blockNum)) {
                    // Block is for the team so assign attributes accordingly
                    $block->setAttribute('block_owner', GameMap::TEAM_BLOCK_NUM);
                    $block->setAttribute('game_team_id', $teamIdForMap->get($blockNum));
                } elseif ($assassinMap->containsStrict($blockNum)) {
                    // Block is for an assassin
                    $block->setAttribute('block_owner', GameMap::ASSASSIN_BLOCK_NUM);
                } else {
                    // Block is for a civilian
                    $block->setAttribute('block_owner', GameMap::CIVILIAN_BLOCK_NUM);
                }

                $block->save();
            }
        });
    }
}
