<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
