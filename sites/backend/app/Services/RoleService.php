<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleService
 * @package App\Services
 */
class RoleService extends BaseService
{
    /**
     * Renews the roles to the player by removing all existing roles
     * and adding the new ones.
     *
     * @param Player $player
     * @param array $roles
     * @return Player
     */
    public function renewRoles(Player $player, array $roles): Player
    {
        $player->syncRoles($roles);

        if (!$player->relationLoaded('roles')) {
            $player->load('roles');
        }

        return $player;
    }

    /**
     * Fetch roles.
     *
     * @param int $limit
     * @param array $filters
     * @param string|null $sort
     * @param string|null $sortBy
     * @return LengthAwarePaginator
     */
    public function all(int $limit, array $filters, ?string $sort, ?string $sortBy): LengthAwarePaginator
    {
        $roles = Role::query();

        // Apply sorting
        $roles->sort(Role::getColumnBySortField($sortBy), $sort);

        foreach ($filters as $filter => $params) {
            // Fetch column for the filter keyword
            $column = Role::getColumnByFilterField($filter);

            switch ($filter) {
                case Role::FILTER_BY['name']:
                    $roles->where($column, 'like', '%' . $params . '%');
                    break;
                case Role::FILTER_BY['created_at']:
                    $roles->between($column, $params['start'], $params['end']);
                    break;
            }
        }

        return $roles->paginate($limit);
    }

    /**
     * Creates a role.
     *
     * @param string $name
     * @param array $permissions
     * @return Role
     */
    public function create(string $name, array $permissions): Role
    {
        $role = null;

        DB::transaction(function () use (&$role, $name, $permissions) {
            $role = Role::create([
                'name' => $name,
            ])
            ->load('permissions')
            ->givePermissionTo($permissions);
        });

        Artisan::call('permission:cache-reset');
        return $role;
    }

    /**
     * Updates a role.
     *
     * @param Role $role
     * @param string $name
     * @param array $permissions
     * @return Role
     */
    public function update(Role $role, string $name, array $permissions): Role
    {
        DB::transaction(function () use ($role, $name, $permissions) {
            // Revoke all existing permissions
            $role->revokePermissionTo($role->getAllPermissions());

            // Add new set of permissions
            $role->givePermissionTo($permissions);

            // Update name of the role
            $role->update([
                'name' => $name,
            ]);
        });

        if (!$role->relationLoaded('permissions')) {
            $role->load('permissions');
        }

        Artisan::call('permission:cache-reset');
        return $role;
    }
}
