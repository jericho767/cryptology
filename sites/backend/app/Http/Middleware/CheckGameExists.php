<?php

namespace App\Http\Middleware;

use App\Services\GameService;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CheckGameExists
 * @package App\Http\Middleware
 */
class CheckGameExists
{
    private $gameService;

    /**
     * CheckGameExists constructor.
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
        $gameId = $request->all('gameId')['gameId'];

        if ($this->gameService->getGame($gameId) !== null) {
            return $next($request);
        }

        throw new Exception(__('errors.game.doesNotExists'));
    }
}
