<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class Permission
 * @package App\Http\Resources
 */
class Permission extends BaseResource
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
            'name' => $this->formatter($this->resource->getAttribute('name')),
        ];
    }

    /**
     * Formats the given permission name to readable text.
     *
     * @param $name
     * @return string
     */
    private function formatter($name): string
    {
        $words = collect(explode('.', $name));

        // Action of the resource
        $verb = $words->pop();

        // Format the case of the resource name
        $words = $words->transform(function ($name) {
            return Str::title(
                implode(' ', preg_split('/(?=[A-Z])/', $name))
            );
        });

        return Str::upper($verb)
            . ' '
            . $words->join(' ');
    }
}
