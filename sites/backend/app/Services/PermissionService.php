<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Collection;

/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionService extends BaseService
{
    /**
     * Gets all permissions.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        /** @var Collection $permissions */
        $permissions = Permission::query()
            ->orderBy('name')
            ->get();

        return $permissions;
    }
}
