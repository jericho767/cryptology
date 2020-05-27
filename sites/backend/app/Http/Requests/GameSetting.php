<?php

namespace App\Http\Requests;

use App\Models\GameSetting as GameSettingModel;

/**
 * Class GameSetting
 * @package App\Http\Requests
 */
class GameSetting extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'map_size' => [
                'required',
                'integer',
                'between:' . GameSettingModel::ALLOWED_MIN_MAP_SIZE . ',' . GameSettingModel::ALLOWED_MAX_MAP_SIZE,
            ],
            'guess_count' => [
                'required',
                'integer',
                'min:1',
                'lt:map_size',
            ],
            'max_teams' => [
                'required',
                'integer',
                'between:' . GameSettingModel::ALLOWED_MIN_TEAMS . ',' . GameSettingModel::ALLOWED_MAX_TEAMS,
            ],
            'min_players' => [
                'required',
                'integer',
                'min:' . GameSettingModel::ALLOWED_MIN_PLAYERS,
                'max:' . GameSettingModel::ALLOWED_MAX_PLAYERS,
                'lte:max_players',
            ],
            'max_players' => [
                'required',
                'integer',
                'min:' . GameSettingModel::ALLOWED_MIN_PLAYERS,
                'max:' . GameSettingModel::ALLOWED_MAX_PLAYERS,
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    protected function passedValidation(): void
    {
        $this->validate([
            'map_size' => 'guess_count_integrity',
            'guess_count' => 'map_size_integrity',
        ]);
    }
}
