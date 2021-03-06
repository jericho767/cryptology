<?php

namespace App\Http\Requests;

use App\Models\GameSetting as GameSettingModel;
use App\Models\Player;
use Illuminate\Validation\Rule;

/**
 * Class GameSetting
 * @package App\Http\Requests
 */
class GameSetting extends BaseRequest
{
    private $isCreateOrUpdate;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // Appropriately set the rules
        switch ($this->route()->getName()) {
            case 'game_settings.create':
            case 'game_settings.update':
                $this->isCreateOrUpdate = true;
                return $this->getDataRules();
            case 'game_settings.index':
                $this->isCreateOrUpdate = false;
                return $this->getListRules();
        }

        return [];
    }

    /**
     * Gets the rules for the list route.
     *
     * @return array
     */
    private function getListRules(): array
    {
        return [
            'filter' => [
                'array',
            ],
            'filter.*' => [
                Rule::in(GameSettingModel::FILTER_BY),
            ],
            'filter.' . GameSettingModel::FILTER_BY['map_size'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['map_size'] . '.start' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['map_size'] . '.end' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['guess_count'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['guess_count'] . '.start' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['guess_count'] . '.end' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_teams'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_teams'] . '.start' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_teams'] . '.end' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['min_players'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['min_players'] . '.start' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['min_players'] . '.end' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_players'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_players'] . '.start' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['max_players'] . '.end' => [
                'integer',
            ],
            'filter.' . GameSettingModel::FILTER_BY['created_by'] => [
                'array',
            ],
            'filter.' . GameSettingModel::FILTER_BY['created_by'] . '.*' => [
                'integer',
                $this->ruleExists((new Player())->getTable()),
            ],
            'filter.'. GameSettingModel::FILTER_BY['created_at'] => [
                'array',
            ],
            'filter.'. GameSettingModel::FILTER_BY['created_at'] . '.start' => [
                'date_format:' . $this->dateFormat,
            ],
            'filter.'. GameSettingModel::FILTER_BY['created_at'] . '.end' => [
                'date_format:' . $this->dateFormat,
            ],
        ] + $this->getBaseListRules(GameSettingModel::SORT_BY);
    }

    /**
     * Get the rules that will be applied to the fields of the model.
     *
     * @return array
     */
    private function getDataRules(): array
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
     * Gets the map_size value.
     *
     * @return int
     */
    public function getMapSize(): int
    {
        return $this->getInt('map_size');
    }

    /**
     * Gets the guess_count value.
     *
     * @return int
     */
    public function getGuessCount(): int
    {
        return $this->getInt('guess_count');
    }

    /**
     * Gets the max_teams value.
     *
     * @return int
     */
    public function getMaxTeams(): int
    {
        return $this->getInt('max_teams');
    }

    /**
     * Gets the min_players value.
     *
     * @return int
     */
    public function getMinPlayers(): int
    {
        return $this->getInt('min_players');
    }

    /**
     * Gets the max_players value.
     *
     * @return int
     */
    public function getMaxPlayers(): int
    {
        return $this->getInt('max_players');
    }

    /**
     * Gets the is_active value.
     *
     * @return int
     */
    public function getIsActive(): int
    {
        return $this->getInt('is_active');
    }

    /**
     * Fetches the filters.
     *
     * @return array
     */
    public function getFilters(): array
    {
        $filters = [];
        $request = $this->get('filter');


        // Add the filters of `created_at`
        $filters['created_at'] = $request[GameSettingModel::FILTER_BY['created_at']] ?? [];

        foreach (GameSettingModel::FILTER_BY as $filter) {
            // Fetch filter from the request
            $filters[$filter] = $request[$filter] ?? [];

            if ($filter === GameSettingModel::FILTER_BY['created_at']) {
                // Typecast the value of the created at to Carbon
                $filters[$filter] = [
                    'start' => isset($request[$filter]['start']) ?
                        $this->toDate($request[$filter]['start'], true) : null,
                    'end' => isset($request[$filter]['end']) ?
                        $this->toDate($request[$filter]['end'], false) : null,
                ];
            } elseif ($filter === GameSettingModel::FILTER_BY['created_by']) {
                // Typecast all IDs inside the array to be integers
                $filters[$filter] = isset($request[$filter]) ? array_map('intval', $request[$filter]) : null;
            } else {
                // Basic filter of `start` and `end` with integer values
                $filters[$filter] = $request[$filter] ?? [];
                // Try to parse value to integer, if 0 is returned, the value of the index will be null instead
                $filters[$filter] = [
                    'start' => intval($filters[$filter]['start'] ?? null) ?: null,
                    'end' => intval($filters[$filter]['end'] ?? null) ?: null,
                ];
            }
        }

        return $filters;
    }

    /**
     * Method call after validations are passed
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        if ($this->isCreateOrUpdate) {
            // Make additional validation for the integrity of the map and guess size
            $this->validate([
                'map_size' => 'guess_count_integrity',
                'guess_count' => 'map_size_integrity',
            ]);
        } else {
            // Validate the filter start and end values
            $this->validateFilterStartEnd();
        }
    }

    /**
     * Validate the start and end integrity of the filter fields
     *
     * @return void
     */
    private function validateFilterStartEnd(): void
    {
        // Add the validations for the start and end values of the filters
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['map_size']);
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['guess_count']);
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['max_teams']);
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['min_players']);
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['max_players']);
        $this->addStartEndValidation('filter.' . GameSettingModel::FILTER_BY['created_at'], true);

        // Run validation
        $this->validator->validate();
    }
}
