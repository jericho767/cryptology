<?php

namespace App\Http\Resources;

use App\Models\GameSetting as GameSettingModel;

/**
 * Class GameSettings
 * @package App\Http\Resources
 */
class GameSetting extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getAttribute('id'),
            'map_size' => $this->resource->getAttribute('map_size'),
            'guess_count' => $this->resource->getAttribute('guess_count'),
            'max_teams' => $this->resource->getAttribute('max_teams'),
            'min_players' => $this->resource->getAttribute('min_players'),
            'max_players' => $this->resource->getAttribute('max_players'),
            'is_active' => $this->resource->getAttribute('is_active') === GameSettingModel::IS_ACTIVE,
            'created_by' => new Player($this->whenLoaded('createdBy')),
            'created_at' => $this->formatDate($this->resource->getAttribute('created_at')),
            'updated_at' => $this->formatDate($this->resource->getAttribute('updated_at')),
            'deleted_at' => $this->formatDate($this->resource->getAttribute('deleted_at')),
        ];
    }
}
