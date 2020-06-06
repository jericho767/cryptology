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
     * The all mighty.
     */
    const SUPER_ADMIN = 'super-admin';
    const SUPER_ADMIN_ID = 1;
}
