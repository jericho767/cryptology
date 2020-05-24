<?php

namespace App\Models;

/**
 * This class only holds the constant roles in `roles` table.
 * For the actual Role model, it is implemented by a package.
 *
 * @package App\Models
 */
class Role
{
    /**
     * Role identifier for Super administrator accounts
     *
     * @var string
     */
    const SUPER_ADMIN = 'super-admin';
    /**
     * Role identifier for administrator accounts
     *
     * @var string
     */
    const ADMIN = 'admin';
    /**
     * Basic ass
     *
     * @var string
     */
    const PLAYER = 'player';
}
