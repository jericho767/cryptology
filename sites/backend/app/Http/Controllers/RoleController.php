<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role as RoleRequest;
use App\Http\Resources\Player;
use App\Services\RoleService;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    private $roleService;

    /**
     * RoleController constructor.
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
    }

    /**
     * Renews the roles of the player.
     *
     * @param RoleRequest $request
     * @return array
     */
    public function renew(RoleRequest $request): array
    {
        return $this->respond(function () use ($request) {
            $player = $request->getPlayer();
            $roles = $request->getRoles();

            return new Player($this->roleService->renewRoles($player, $roles));
        });
    }
}
