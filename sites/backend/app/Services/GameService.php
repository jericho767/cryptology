<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameMap;
use App\Models\GameTeam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
     * Gets the game
     *
     * @param int $gameId
     * @param array $relations
     * @return Game|null
     */
    public function getGame(int $gameId, array $relations = []): ?Game
    {
        /** @var Game $game */
        if ($relations !== '') {
            $game = Game::with($relations);
        } else {
            $game = Game::query();
        }

        $game = $game->find($gameId);

        return $game;
    }

    /**
     * Gets the game where the game team belongs to
     *
     * @param int $gameTeamId
     * @return Game|null
     */
    public function getGameOfParticipant(int $gameTeamId): ?Game
    {
        /** @var Game $game */
        $game = Game::query()
            ->whereHas('participants', function (Builder $builder) use ($gameTeamId): void {
                $builder->where('id', $gameTeamId);
            })
            ->first();

        return $game;
    }

    /**
     * Checks if the game is running
     *
     * @param int $gameId
     * @return bool
     */
    public function isGameRunning(int $gameId): bool
    {
        return Game::query()->find($gameId)->getAttribute('winner') === null;
    }

    /**
     * Checks the parameters if the game should have a winner
     *
     * @param $gameId
     * @return int|null game_team id of the winner
     */
    public function updateGameWinner($gameId): ?int
    {
        // Fetch the game
        $game = Game::query()->find($gameId);
        // Gets all the blocks of the game
        $blocks = GameMap::with(['guessedAt.gameTurn.turnOrder.gameTeamPlayer'])
            ->where('game_id', $gameId)
            ->get();
        // Gets all the participants of the game
        $gameTeamIds = GameTeam::query()
            ->where('game_id', $gameId)
            ->get()
            ->pluck('id');
        // Check if all the assassins has been guessed
        $hasUnGuessedAssassin = $blocks
            ->filter(function (GameMap $block) {
                $isGuessed = $block->getRelation('guessedAt') !== null;
                $isAssassin = $block->getAttribute('block_owner') === GameMap::ASSASSIN_BLOCK_NUM;

                return !$isGuessed && $isAssassin;
            })->count() > 0;
        // Contains the game team id of the winner
        $winnerId = null;

        // All assassins has been guessed
        if (!$hasUnGuessedAssassin) {
            $loserIds = $blocks
                ->where('block_owner', GameMap::ASSASSIN_BLOCK_NUM)
                ->transform(function (GameMap $gameMap) {
                    return [
                        'game_team_id' => $gameMap
                            ->getRelation('guessedAt') // Gets the TurnGuess
                            ->getRelation('gameTurn') // Gets which turn
                            ->getRelation('turnOrder') // Gets the TurnOrder
                            ->getRelation('gameTeamPlayer') // Gets the player of the turn
                            ->getAttribute('game_team_id') // Get the `game_team_id`
                    ];
                })
                ->pluck('game_team_id')
                ->values();

            // Fetch the winner by filtering out the losers
            $winnerId = $gameTeamIds->filter(function (int $value) use ($loserIds) {
                    return !$loserIds->containsStrict($value);
                })
                ->first();
        } else {
            // Fetch all team blocks
            $teamBlocks = $blocks
                ->whereNotNull('game_team_id')
                ->groupBy('game_team_id');

            // Look for the winner
            $teamBlocks->each(function (Collection $blocks, $blockTeamIdOwner) use ($game, &$winnerId): bool {
                $unguessedBlocks = $blocks->whereNull('guessedAt');

                // All blocks have been guessed
                if ($unguessedBlocks->count() === 0) {
                    // We have a winner
                    $winnerId = $blockTeamIdOwner;

                    // End search of winner
                    return false;
                }

                // Look again for the winner
                return true;
            });
        }

        // Check if there's a winner
        if ($winnerId !== null) {
            $game->setAttribute('winner', $winnerId);
            $game->save();
        }

        return $winnerId;
    }
}
