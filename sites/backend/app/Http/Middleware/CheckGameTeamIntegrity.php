<?php

namespace App\Http\Middleware;

use App\Services\GameService;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CheckGameTeamIntegrity
 * @package App\Http\Middleware
 */
class CheckGameTeamIntegrity
{
    private $gameService;

    /**
     * CheckGameTeamIntegrity constructor.
     * @param GameService $gService
     */
    public function __construct(GameService $gService)
    {
        $this->gameService = $gService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        $params = $request->all(['gameTeamId', 'gameId']);
        // Fetch the gameTeamId
        $gameTeamId = $params['gameTeamId'];
        // Fetch the gameId
        $gameId = $params['gameId'];

        // Incomplete parameters
        if ($gameTeamId === null || $gameId === null) {
            throw new Exception('Get the fuck out of here.');
        }

        // Fetch the game the participant is in
        $game = $this->gameService->getGameOfParticipant($gameTeamId);

        // Nonexistent game
        if ($game === null) {
            throw new Exception('What are you? Some kind of phantom player?');
        }

        // Check if the game matches with the one given
        if (intval($gameId) !== $game->getAttribute('id')) {
            throw new Exception('You lost motherfucker? Get your ass off here.');
        }

        return $next($request);
    }
}
