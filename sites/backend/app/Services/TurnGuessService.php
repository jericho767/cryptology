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
     * Validates the integrity of the `$gameTeamId` and `$gameId`
     * checking if its capable for playing/guessing
     *
     * @param int $gameTeamId
     * @param int $gameId
     * @return void
     * @throws Exception
     */
    private function validateBeforeGuess(int $gameTeamId, int $gameId): void
    {
        if (!$this->gameService->isGameRunning($gameId)) {
            // Game is not running, what the fuck you doing?
            throw new Exception('Game is not running.');
        } elseif (!$this->gameService->isValidParticipant($gameTeamId, $gameId)) {
            // Wrong server you idiot. The fuck?!
            throw new Exception('Invalid participant.');
        }
    }

    /**
     * Do stuffs when a team has guessed an assassin
     *
     * @param int $gameTeamId
     * @param int $gameId
     * @return TurnOrder|null
     * @throws Exception
     */
    public function guessedAnAssassinBlock(int $gameTeamId, int $gameId): ?TurnOrder
    {
        $this->validateBeforeGuess($gameTeamId, $gameId);

        // Eliminate the team
        $this->turnOrderService->eliminateTeam($gameTeamId);

        // Get next turn
        return $this->turnOrderService->getCurrentTurn($gameId);
    }

    /**
     * Do stuffs when a team has guessed a civilian
     *
     * @param int $gameTeamId
     * @param int $gameId
     * @return TurnOrder|null
     * @throws Exception
     */
    public function guessedACivilianBlock(int $gameTeamId, int $gameId): ?TurnOrder
    {
        $this->validateBeforeGuess($gameTeamId, $gameId);

        // End turn proceed to next turn
        return $this->turnOrderService->getCurrentTurn($gameId);
    }
}
