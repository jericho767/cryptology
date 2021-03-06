<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role as RoleRequest;
use App\Http\Resources\Player;
use App\Http\Resources\Role;
use App\Http\Resources\RoleCollection;
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
     * List of roles.
     *
     * @param RoleRequest $request
     * @return array
     */
    public function index(RoleRequest $request): array
    {
        return $this->respond(function () use ($request) {
            return new RoleCollection($this->roleService->all(
                $request->getLimit(),
                $request->getFilters(),
                $request->getSort(),
                $request->getSortBy()
            ));
        });
    }

    /**
     * Create a role.
     *
     * @param RoleRequest $request
     * @return array
     */
    public function store(RoleRequest $request): array
    {
        return $this->respond(function () use ($request) {
            return new Role(
                $this->roleService->create(
                    $request->getName(),
                    $request->getPermissions()
                )
            );
        });
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

    /**
     * Updates a role.
     *
     * @param RoleRequest $request
     * @return array
     */
    public function update(RoleRequest $request): array
    {
        return $this->respond(function () use ($request) {
            return new Role(
                $this->roleService->update(
                    $request->getRouteRole(),
                    $request->getName(),
                    $request->getPermissions()
                )
            );
        });
    }
}
