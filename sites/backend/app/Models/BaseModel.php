<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel
 * @package App\Models
 */
class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * Gets the column to be filtered base on the filter field value.
     *
     * @param string $filterField
     * @return string
     */
    public static function getColumnByFilterField(string $filterField): string
    {
        return collect(get_called_class()::FILTER_BY)
            ->filter(function ($field) use ($filterField) {
                return $field === $filterField;
            })
            ->keys()
            ->first();
    }

    /**
     * Gets the column to be sorted base on the sort field value.
     *
     * @param string $sortField
     * @return string
     */
    public static function getColumnBySortField(string $sortField): string
    {
        return collect(get_called_class()::SORT_BY)
            ->filter(function ($field) use ($sortField) {
                return $field === $sortField;
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

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        if (!is_array($this->getKeyName())) {
            return parent::save($options);
        }

        // Fire Event for others to hook
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // Prepare query for inserting or updating
        $query = $this->newQueryWithoutScopes();

        if ($this->exists) { // Perform Update
            if (count($this->getDirty()) > 0) {
                // Fire Event for others to hook
                if ($this->fireModelEvent('updating') === false) {
                    return false;
                }
                // Touch the timestamps
                if ($this->timestamps) {
                    $this->updateTimestamps();
                }

                //=================
                // START FIX
                //=================

                // Convert primary key into an array if it is not an array
                $primary = is_array($this->getKeyName()) ? $this->getKeyName() : [$this->getKeyName()];

                // Fetch the primary key(s) values before any changes
                $unique = array_intersect_key($this->original, array_flip($primary));

                // Fetch the primary key(s) values after any changes
                $unique = !empty($unique) ?
                    $unique :
                    array_intersect_key($this->getAttributes(), array_flip($primary));
                // Fetch the element of the array if the array contains only a single element
                // $unique = (count($unique) <> 1) ? $unique : reset($unique);
                // Apply SQL logic
                $query->where($unique);

                //=================
                // END FIX
                //=================

                // Update the records
                $query->update($this->getDirty());

                // Fire an event for hooking into
                $this->fireModelEvent('updated', false);
            }
        } else { // Insert
            // Fire an event for hooking into
            if ($this->fireModelEvent('creating') === false) {
                return false;
            }

            // Touch the timestamps
            if ($this->timestamps) {
                $this->updateTimestamps();
            }

            // Retrieve the attributes
            $attributes = $this->attributes;

            if ($this->incrementing && !is_array($this->getKeyName())) {
                $this->insertAndSetId($query, $attributes);
            } else {
                $query->insert($attributes);
            }

            // Set exists to true in case someone tries to update it during an event
            $this->exists = true;

            // Fire an event for hooking into
            $this->fireModelEvent('created', false);
        }

        // Fires an event
        $this->fireModelEvent('saved', false);

        // Sync
        $this->original = $this->attributes;

        // Touches all relations
        if (array_get($options, 'touch', true)) {
            $this->touchOwners();
        }

        return true;
    }
}
