<?php

namespace App\Http\Middleware;

use App\Models\Player;
use App\Models\Role;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CheckUpdatedRoleIsValid
 * @package App\Http\Middleware
 */
class HandleRole
{
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
        switch ($request->route()->getName()) {
            case 'roles.renew':
                return $this->canRenew($request, $next);
            case 'roles.update':
                return $this->canUpdate($request, $next);
        }

        return $next($request);
    }

    /**
     * Perform middleware for updating of role.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    private function canUpdate(Request $request, Closure $next)
    {
        /** @var Player $loggedInUser */
        $loggedInUser = $request->user();

        /** @var Role $role */
        $role = $request->route('role');

        // Check if the logged in user has the role
        if ($loggedInUser->hasRole($role->getAttribute('name'))) {
            // Cannot update role that is given to the current logged in user
            throw new Exception(__('errors.role.cannotUpdateOwnRole'));
        } elseif ($role->getAttribute('name') === Role::SUPER_ADMIN) {
            // Super admin role cannot be updated
            throw new Exception(__('errors.role.cannotUpdateSuperAdmin'));
        }

        return $next($request);
    }

    /**
     * Perform middleware for renew of role.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    private function canRenew(Request $request, Closure $next)
    {
        /** @var Player $player */
        $player = $request->route('player');

        /** @var Player $loggedInUser */
        $loggedInUser = $request->user();

        // Player being handled is a super admin
        if ($player->hasRole(Role::SUPER_ADMIN)) {
            // Logged in user is not a super admin
            if (!$loggedInUser->hasRole(Role::SUPER_ADMIN)) {
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
