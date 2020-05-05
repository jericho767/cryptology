<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GameService
 * @package App\Services
 */
class GameService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the expected number of assassins for given participant count
     *
     * @param int $participantCount
     * @return int
     */
    public function getNumOfAssassins(int $participantCount): int
    {
        return $participantCount - 1;
    }

    /**
     * Check if the game is still running given a `game_teams.id` value
     *
     * @param int $gameId
     * @return bool
     */
    public function isGameRunning(int $gameId): bool
    {
        $game = Game::query()->where('id', $gameId)->firstOrFail();

        // Game is still running when the winner value is still `null`
        return $game->getAttribute('winner') === null;
    }

    /**
     * Check if the given `$gameTeamId` is from `$gameId`
     *
     * @param int $gameTeamId
     * @param int $gameId
     * @return bool
     */
    public function isValidParticipant(int $gameTeamId, int $gameId): bool
    {
        return Game::query()
            ->whereHas('participants', function (Builder $builder) use ($gameTeamId): void {
                $builder->where('id', $gameTeamId);
            })
            ->where('id', $gameId)
            ->exists();
    }
}
