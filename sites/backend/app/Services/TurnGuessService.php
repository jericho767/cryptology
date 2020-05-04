<?php

namespace App\Services;

use App\Models\TurnOrder;
use Exception;

/**
 * Class TurnGuessService
 * @package App\Services
 */
class TurnGuessService extends BaseService
{
    private $gameService;
    private $turnOrderService;

    /**
     * TurnGuessService constructor.
     * @param GameService $gameService
     * @param TurnOrderService $toService
     */
    public function __construct(GameService $gameService, TurnOrderService $toService)
    {
        parent::__construct();
        $this->gameService = $gameService;
        $this->turnOrderService = $toService;
    }

    /**
     * Do stuffs when a team has guessed an assassin
     *
     * @param int $gameTeamId
     * @param int $gameId
     * @return TurnOrder|null
     * @throws Exception
     */
    public function guessedAnAssassin(int $gameTeamId, int $gameId): ?TurnOrder
    {
        if (!$this->gameService->isGameRunningByTeamId($gameTeamId)) {
            // Game is not running, what the fuck?
            throw new Exception('Game is not running.');
        }

        // Eliminate the team
        $this->turnOrderService->eliminateTeam($gameTeamId);

        // Get next turn
        return $this->turnOrderService->getCurrentTurn($gameId);
    }
}
