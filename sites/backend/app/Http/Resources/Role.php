<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/**
 * Class Role
 * @package App\Http\Resources
 */
class Role extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getAttribute('id'),
            'name' => $this->resource->getAttribute('name'),
            'created_at' => $this->formatDate($this->resource->getAttribute('created_at')),
        ];
    }
}
