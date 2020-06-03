<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * Class BaseResourceCollection
 * @package App\Http\Resources
 */
class BaseResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return Collection
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
