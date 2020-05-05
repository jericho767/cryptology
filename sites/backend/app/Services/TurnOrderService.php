<?php

namespace App\Services;

use App\Models\GameMap;
use App\Models\GameTurn;
use App\Models\TurnGuess;
use App\Models\TurnOrder;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class TurnOrderService
 * @package App\Services
 */
class TurnOrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the turn order from the next team
     *
     * @param Collection $turnOrdersByTeam
     * @param int $lastMovedTeamId
     * @param int $gameId
     * @return TurnOrder|null
     */
    private function getNextTeamTurnOrder(
        Collection $turnOrdersByTeam,
        int $lastMovedTeamId,
        int $gameId
    ): ?TurnOrder
    {
        // Get the order of turns of the teams
        $turnOrdersTeamIds = $turnOrdersByTeam->collect()
            ->transform(function (Collection $turnOrders): Collection {
                return collect([
                    'game_team_id' => $turnOrders
                        ->first() // Gets sample item from the collection
                        ->getRelation('gameTeamPlayer') // Gets the GameTeamPlayer model
                        ->getAttribute('game_team_id'), // Fetch the `game_team_id`, it's all that is needed
                ]);
            })
            ->values(); // Reset array indeces
        // Determine the index of the team in the order
        $indexOfTheLastMovedTeam = $turnOrdersTeamIds
            ->whereStrict('game_team_id', $lastMovedTeamId)
            ->keys()
            ->first();

        // Check if there's a next team
        if (isset($turnOrdersTeamIds[$indexOfTheLastMovedTeam + 1])) {
            // Fetch the id of the game team to make the next turn
            $gameTeamId = $turnOrdersTeamIds
                ->get($indexOfTheLastMovedTeam + 1)
                ->get('game_team_id');
        } else {
            /*
             * Fetch the id of the game team to make the next turn
             * It'll be the first index
             */
            $gameTeamId = $turnOrdersTeamIds->first()->get('game_team_id');
        }

        return $this->getTurnFromTeamTurnOrders($turnOrdersByTeam, $gameTeamId, $gameId);
    }

    /**
     * Gets the player that will play th
     *
     * @param Collection $turnOrdersByTeam
     * @param int $gameTeamId
     * @param int $gameId
     * @return TurnOrder|null
     */
    private function getTurnFromTeamTurnOrders(
        Collection $turnOrdersByTeam,
        int $gameTeamId,
        int $gameId
    ): ?TurnOrder
    {
        // Gets the turn orders belonging to the team to make the current turn
        $teamTurnOrders = $turnOrdersByTeam->get($gameTeamId);
        $currentMove = $teamTurnOrders->firstWhere('has_played', TurnOrder::HAS_NOT_PLAYED);

        // All players in the team has already made their turn
        if ($currentMove === null) {
            return $this->resetTurnOrders($gameId, $gameTeamId)->first();
        }

        return $currentMove;
    }

    /**
     * Gets the next turn base on the last moved made
     *
     * @param TurnOrder $latestTurnOrder
     * @param Collection $turnOrdersByTeam
     * @param int $gameId
     * @return TurnOrder|null
     * @throws Exception
     */
    private function getCurrentTurnBaseOnLastMove(
        TurnOrder $latestTurnOrder,
        Collection $turnOrdersByTeam,
        int $gameId
    ): ?TurnOrder
    {
        // Gets the last game turn made by the turn order
        /** @var GameTurn $lastGameTurn */
        $lastGameTurn = $latestTurnOrder
            ->getRelation('turns')
            ->sortByDesc('id')
            ->first();

        // Fetch the last guess from that turn
        /** @var TurnGuess $lastGuess */
        $lastGuess = $lastGameTurn
            ->getRelation('guesses')
            ->sortByDesc('id')
            ->first();

        // Determine the team of the player who last made the turn
        $lastMovedTeamId = $latestTurnOrder
            ->getRelation('gameTeamPlayer')
            ->getAttribute('game_team_id');

        /** @var GameMap $lastBlockGuessed */
        $lastBlockGuessed = $lastGuess->getRelation('gameMap');

        switch ($lastBlockGuessed->getAttribute('block_owner')) {
            case GameMap::ASSASSIN_BLOCK_NUM:
            case GameMap::CIVILIAN_BLOCK_NUM:
                return $this->getNextTeamTurnOrder($turnOrdersByTeam, $lastMovedTeamId, $gameId);
            case GameMap::TEAM_BLOCK_NUM:
                // Team block owner
                if ($lastBlockGuessed->getAttribute('game_team_id') === $lastMovedTeamId) {
                    // The guess is from the same team, continue the turn of the team
                    return $this->getTurnFromTeamTurnOrders($turnOrdersByTeam, $lastMovedTeamId, $gameId);
                } else {
                    // The guess is from other team, terminate team turn
                    return $this->getNextTeamTurnOrder($turnOrdersByTeam, $lastMovedTeamId, $gameId);
                }
                break;
            default:
                throw new Exception('Unknown block.');
        }
    }

    /**
     * Gets the current TurnOrder
     *
     * @param int $gameId
     * @return TurnOrder|null
     * @throws Exception
     */
    public function getCurrentTurn(int $gameId): ?TurnOrder
    {
        // Retrieve all playable turn orders in the game
        $turnOrders = TurnOrder::with(['gameTeamPlayer', 'turns.guesses.gameMap'])
            ->whereHas('gameTeamPlayer.gameTeam', function (Builder $builder) use ($gameId): void {
                $builder->where('game_id', $gameId);
            })
            ->where('has_played', '!=', TurnOrder::CANNOT_PLAY_ANYMORE)
            ->orderBy('id')
            ->get();

        // There's no playable turn, the game has/already been ended
        if ($turnOrders->isEmpty()) {
            // No more turns
            return null;
        }

        // Turn orders grouped by team
        $turnOrdersByTeam = $turnOrders->groupBy(function (TurnOrder $turnOrder): int {
                return $turnOrder
                    ->getRelation('gameTeamPlayer') // Gets the GameTeamPlayer model
                    ->getAttribute('game_team_id'); // This will be grouping, by `game_team_id`
            })
            ->transform(function (Collection $turnOrders): Collection {
                // Sort the turn orders inside each team by `turn_orders.id`
                return $turnOrders->sortBy('id');
            });

        // There's only 1 team left
        if ($turnOrdersByTeam->count() < 2) {
            // No point playing when there's no opponent
            return null;
        }

        // Get the last player who made their turn
        /** @var TurnOrder $lastMoved */
        $lastMoved = $turnOrders
            ->sortByDesc('updated_at')
            ->where('has_played', TurnOrder::HAS_PLAYED)
            ->first();

        // No one made a move yet or a round of turns has made
        if ($lastMoved === null) {
            // Retrieve the first player to move base on the `turn_orders.id`
            return $turnOrders->first();
        } else {
            // Retrieve next turn base on the guess from the last turn
            return $this->getCurrentTurnBaseOnLastMove($lastMoved, $turnOrdersByTeam, $gameId);
        }
    }

    /**
     * Resets the status of each playable[1] turn orders so it'll be rotated again
     * [1] Turns whose status can still be played and not `CANNOT_PLAY_ANYMORE`
     *
     * @param int $gameId
     * @param int $gameTeamId
     * @return Collection
     */
    private function resetTurnOrders(int $gameId, int $gameTeamId): Collection
    {
        // Callback to filter the turn orders
        $callback = function (Builder $builder) use ($gameId, $gameTeamId): void {
            $builder->where('game_id', $gameId);

            if ($gameTeamId !== null) {
                $builder->where('id', $gameTeamId);
            }
        };

        $turnOrders = TurnOrder::query()
            ->whereHas('gameTeamPlayer.gameTeam', $callback)
            ->where('has_played', '!=', TurnOrder::CANNOT_PLAY_ANYMORE)
            ->orderBy('id')
            ->get();

        // Resets the `has_played` value
        $turnOrders->each(function (TurnOrder $turnOrder): void {
            $turnOrder->setAttribute('has_played', TurnOrder::HAS_NOT_PLAYED);
            $turnOrder->save();
        });

        return $turnOrders;
    }

    /**
     * Eliminates a team from the turn order stack
     *
     * @param int $gameTeamId
     */
    public function eliminateTeam(int $gameTeamId): void
    {
        TurnOrder::query()
            ->whereHas('gameTeamPlayer.gameTeam', function (Builder $builder) use ($gameTeamId): void {
                $builder->where('id', $gameTeamId);
            })
            ->get()
            ->each(function (TurnOrder $turnOrder) {
                // Set the status of all game team players' `has_played` to `CANNOT_PLAY_ANYMORE`
                $turnOrder->setAttribute('has_played', TurnOrder::CANNOT_PLAY_ANYMORE);
                $turnOrder->save();
            });
    }
}
