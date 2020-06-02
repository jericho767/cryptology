<?php

namespace App\Http\Resources;

use App\Models\BaseModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class BaseResource
 * @package App\Http\Resources
 * @property BaseModel $resource
 */
class BaseResource extends JsonResource
{
    /**
     * Outputted format of the date.
     *
     * @var string
     */
    private const DATE_FORMAT = 'Y-m-d';

    /**
     * Formats a given date.
     *
     * @param Carbon $date
     * @param string $format
     * @return string
     */
    protected function formatDate(Carbon $date = null, string $format = self::DATE_FORMAT): string
    {
        if ($date === null) {
            return '';
        }

        return $date->format($format);
    }
}
