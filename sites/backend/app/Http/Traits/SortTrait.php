<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait SortTrait
 * @package App\Http\Traits
 */
trait SortTrait
{
    /**
     * Sorting.
     *
     * @param Builder $query
     * @param string|null $column
     * @param string|null $sort
     */
    public function scopeSort(Builder $query, ?string $column, ?string $sort): void
    {
        // Apply sorting
        if ($column !== null && $sort !== null) {
            $query->orderBy($column, $sort);
        } else {
            // Default sorting
            $query->orderByDesc('id');
        }
    }

    /**
     * Gets the column to be sorted base on the sort field value.
     *
     * @param string|null $sortField
     * @return string
     */
    public static function getColumnBySortField(?string $sortField): ?string
    {
        if ($sortField === null) {
            return null;
        }

        return collect(get_called_class()::SORT_BY)
            ->filter(function ($field) use ($sortField) {
                return $field === $sortField;
            })
            ->keys()
            ->first();
    }
}
