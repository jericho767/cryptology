<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/**
 * Class Player
 * @package App\Http\Resources
 */
class Player extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getAttribute('id'),
            'name' => $this->resource->getAttribute('name'),
            'email' => $this->resource->getAttribute('email'),
            'phone_number' => $this->resource->getAttribute('phone_number'),
            'email_verified_at' => $this->formatDate($this->resource->getAttribute('email_verified_at')),
            'created_at' => $this->formatDate($this->resource->getAttribute('created_at')),
            'updated_at' => $this->formatDate($this->resource->getAttribute('updated_at')),
            'deleted_at' => $this->formatDate($this->resource->getAttribute('deleted_at')),
        ];
    }
}
