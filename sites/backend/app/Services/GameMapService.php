<?php

namespace App\Services;

use App\Models\GameMap;
use Illuminate\Support\Collection;

/**
 * Class GameMapService
 * @package App\Services
 */
class GameMapService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the blocks that needs to be guessed by the team
     *
     * @param int $gameTeamId       the id of the game team
     * @param bool|null $isGuessed  fetch the unguessed or guessed ones. `null` for both.
     * @return Collection
     */
    public function getBlocksOfTeam(int $gameTeamId, bool $isGuessed = null): Collection
    {
        $query = GameMap::query()
            ->where('game_team_id', $gameTeamId);

        if ($isGuessed) {
            // Fetch only the guessed ones
            $query->has('guessedAt', '>', 0);
        } elseif ($isGuessed === false) {
            // Fetch only the unguessed ones
            $query->has('guessedAt', '<', 1);
        }

        return $query->get();
    }

    /**
     * Gets the unguessed blocks of the game.
     *
     * @param int $gameId   id of the game of course
     * @return Collection
     */
    public function getUnguessedBlocks(int $gameId): Collection
    {
        return GameMap::query()
            ->where('game_id', $gameId)
            ->has('guessedAt', '<', 1)
            ->get();
    }
}
