<?php

namespace App\Http\Middleware;

use App\Models\Player;
use App\Models\Role as Roles;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class LoginUserAndRoleAction
 * @package App\Http\Middleware
 */
class LoginUserAndRoleAction
{
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
        /** @var Player $player */
        $player = $request->route('player');

        /** @var Player $loggedInUser */
        $loggedInUser = $request->user();

        // Player being handled is a super admin
        if ($player->hasRole(Roles::SUPER_ADMIN)) {
            // Logged in user is not a super admin
            if (!$loggedInUser->hasRole(Roles::SUPER_ADMIN)) {
                throw new Exception(__('errors.role.playerIsSuperAdmin'));
            }
        }

        // Cannot handle own roles
        if ($player->getAttribute('id') === $loggedInUser->getAttribute('id')) {
            throw new Exception(__('errors.role.ownAccount'));
        }

        return $next($request);
    }
}
