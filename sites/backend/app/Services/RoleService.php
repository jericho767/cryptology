<?php

namespace App\Services;

use App\Models\Player;

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
}
