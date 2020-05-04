<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

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
     * @param int $gameTeamId
     * @return bool
     */
    public function isGameRunningByTeamId(int $gameTeamId): bool
    {
        $game = Game::query()
            ->whereHas('participants', function (Builder $builder) use ($gameTeamId): void {
                $builder->where('id', $gameTeamId);
            })
            ->firstOrFail();

        // Game is still running when the winner value is still `null`
        return $game->getAttribute('winner') === null;
    }
}
