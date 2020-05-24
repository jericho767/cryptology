<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class BaseResource
 * @package App\Http\Resources
 */
class BaseResource extends JsonResource
{
    /**
     * Formats a given date.
     *
     * @param Carbon $date
     * @param bool $withTime
     * @return string
     */
    protected function formatDate(Carbon $date = null, bool $withTime = false): string
    {
        if ($date === null) {
            return '';
        }

        $format = 'Y-m-d';
        $format .= $withTime ? 'G:i:s' : '';
        return $date->format($format);
    }
}
