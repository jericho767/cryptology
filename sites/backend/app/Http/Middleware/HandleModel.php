<?php

namespace App\Http\Middleware;

use App\Http\Kernel;
use App\Models\Player;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

/**
 * A middleware interceptor responsible for checking
 * if the logged in user can handle the model
 *
 * @package App\Http\Middleware
 */
class HandleModel
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string $middlewareIndex
     * @param string $model
     * @return void|mixed
     */
    public function handle(Request $request, Closure $next, string $middlewareIndex, string $model)
    {
        /** @var Player $player */
        $player = $request->user();

        if ($player->hasRole(Role::SUPER_ADMIN)) {
            // It's the all powerful! Bypass that model handler middleware
            return $next($request);
        }

        // Fetch the middleware for the given index
        $middleware = app(Kernel::class)->getRouteMiddleware()[$middlewareIndex];

        $callback = function ($request) use ($next) {
            // Proceed
            return $next($request);
        };

        return app($middleware)->handle($request, $callback, $request->route($model));
    }
}
