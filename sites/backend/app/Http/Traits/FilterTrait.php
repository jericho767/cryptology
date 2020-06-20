<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait FilterTrait
 * @package App\Http\Traits
 */
trait FilterTrait
{
    /**
     * Gets the column to be filtered base on the filter field value.
     *
     * @param string|null $filterField
     * @return string
     */
    public static function getColumnByFilterField(?string $filterField): ?string
    {
        if ($filterField === null) {
            return null;
        }

        return collect(get_called_class()::FILTER_BY)
            ->filter(function ($field) use ($filterField) {
                return $field === $filterField;
            })
            ->keys()
            ->first();
    }

    /**
     * Scope for adding where clause for in between filters.
     *
     * @param Builder $query
     * @param string $column
     * @param Carbon|int $start
     * @param Carbon|int $end
     */
    public function scopeBetween(Builder $query, string $column, $start, $end): void
    {
        if ($start !== null && $end !== null) {
            // Both start and end are set, use between clause
            $query->whereBetween($column, [$start, $end]);
        } elseif ($start === null && $end !== null) {
            // Only the end part is set
            if ($end instanceof Carbon) {
                $query->whereDate($column, '<=', $end);
            } else {
                $query->where($column, '<=', $end);
            }
        } elseif ($start !== null && $end === null) {
            // Only the start part is set
            if ($start instanceof Carbon) {
                $query->whereDate($column, '>=', $start);
            } else {
                $query->where($column, '>=', $start);
            }
        }
    }
}
