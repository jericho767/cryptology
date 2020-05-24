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
     * List of all roles
     *
     * @var array
     */
    const ALL = [
        // Role identifier for Super administrator accounts
        'super.admin' => 'super-admin',
        // Role identifier for administrator accounts
        'admin' => 'admin',
    ];
}
