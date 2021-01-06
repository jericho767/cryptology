<?php

namespace App\Http\Middleware;

use App\Services\GameService;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CheckGameIsRunning
 * @package App\Http\Middleware
 */
class CheckGameIsRunning
{
    private $gameService;

    /**
     * CheckGameIsRunning constructor.
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
    public function handle(Request $request, Closure $next)
    {
        /*
         * Fetch `gameId` from the request
         * Use `->all` instead of `->get` and `->post`
         * to be able to adapt to any type method request
         *
         * NOTE: `->post` is flexible to any method
         *       but it'll be confusing to use when
         *       it does not match with the actual
         *       method type
         */
        $gameId = $request->all('gameId')['gameId'];

        if ($this->gameService->isGameRunning($gameId)) {
            return $next($request);
        }

        throw new Exception(__('errors.game.notRunning'));
    }
}
