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

    /**
     * Gets the map_size value
     *
     * @return int
     */
    public function getMapSize(): int
    {
        return intval($this->get('map_size'));
    }

    /**
     * Gets the guess_count value
     *
     * @return int
     */
    public function getGuessCount(): int
    {
        return intval($this->get('guess_count'));
    }

    /**
     * Gets the max_teams value
     *
     * @return int
     */
    public function getMaxTeams(): int
    {
        return intval($this->get('max_teams'));
    }

    /**
     * Gets the min_players value
     *
     * @return int
     */
    public function getMinPlayers(): int
    {
        return intval($this->get('min_players'));
    }

    /**
     * Gets the max_players value
     *
     * @return int
     */
    public function getMaxPlayers(): int
    {
        return intval($this->get('max_players'));
    }

    /**
     * Gets the is_active value
     *
     * @return int
     */
    public function getIsActive(): int
    {
        return intval($this->get('is_active'));
    }

    /**
     * Method call after validations are passed
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // Make additional validation for the integrity of the map and guess size
        $this->validate([
            'map_size' => 'guess_count_integrity',
            'guess_count' => 'map_size_integrity',
        ]);
    }
}
