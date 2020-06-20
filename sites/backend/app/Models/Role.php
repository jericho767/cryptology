<?php

namespace App\Models;

use App\Http\Traits\FilterTrait;
use App\Http\Traits\SortTrait;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 * @package App\Models
 */
class Role extends SpatieRole
{
    use SortTrait;
    use FilterTrait;

    /**
     * The all mighty.
     */
    const SUPER_ADMIN = 'super-admin';
    const SUPER_ADMIN_ID = 1;

    /**
     * Fields and logic that can be sorted by.
     * key -> attribute name
     * value -> sort keyword
     *
     * @var array
     */
    const SORT_BY = [
        'name' => 'name',
        'created_at' => 'created_at',
    ];

    /**
     * Fields and logic that can be filtered by.
     * key -> attribute name
     * value -> filter keyword
     *
     * @var array
     */
    const FILTER_BY = [
        'name' => 'name',
        'created_at' => 'created_at',
    ];
}
