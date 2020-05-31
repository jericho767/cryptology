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
            'sortBy' => [
                Rule::in(GameSettingModel::SORT_BY),
                'required_with:sort',
            ],
            'sort' => [
                'required_with:sortBy',
                Rule::in(['asc', 'desc']),
            ],
            'filter' => [
                'array',
            ],
            'filter.*' => [
                Rule::in(GameSettingModel::FILTER_BY),
            ],
            'filter.map_size' => [
                'array',
            ],
            'filter.map_size.start' => [
                'integer',
            ],
            'filter.map_size.end' => [
                'integer',
            ],
            'filter.guess_count' => [
                'array',
            ],
            'filter.guess_count.start' => [
                'integer',
            ],
            'filter.guess_count.end' => [
                'integer',
            ],
            'filter.max_teams' => [
                'array',
            ],
            'filter.max_teams.start' => [
                'integer',
            ],
            'filter.max_teams.end' => [
                'integer',
            ],
            'filter.min_players' => [
                'array',
            ],
            'filter.min_players.start' => [
                'integer',
            ],
            'filter.min_players.end' => [
                'integer',
            ],
            'filter.max_players' => [
                'array',
            ],
            'filter.max_players.start' => [
                'integer',
            ],
            'filter.max_players.end' => [
                'integer',
            ],
            'filter.created_by' => [
                'array',
            ],
            'filter.created_by.*' => [
                'integer',
                Rule::exists((new Player())->getTable(), 'id')->whereNull('deleted_at')
            ],
            'filter.created_at' => [
                'array',
            ],
            'filter.created_at.start' => [
                'date_format:' . $this->dateFormat,
            ],
            'filter.created_at.end' => [
                'date_format:' . $this->dateFormat,
            ],
        ];
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
        return intval($this->get('map_size'));
    }

    /**
     * Gets the guess_count value.
     *
     * @return int
     */
    public function getGuessCount(): int
    {
        return intval($this->get('guess_count'));
    }

    /**
     * Gets the max_teams value.
     *
     * @return int
     */
    public function getMaxTeams(): int
    {
        return intval($this->get('max_teams'));
    }

    /**
     * Gets the min_players value.
     *
     * @return int
     */
    public function getMinPlayers(): int
    {
        return intval($this->get('min_players'));
    }

    /**
     * Gets the max_players value.
     *
     * @return int
     */
    public function getMaxPlayers(): int
    {
        return intval($this->get('max_players'));
    }

    /**
     * Gets the is_active value.
     *
     * @return int
     */
    public function getIsActive(): int
    {
        return intval($this->get('is_active'));
    }

    /**
     * Gets the sorted property.
     *
     * @return null|string
     */
    public function getSort(): ?string
    {
        return $this->get('sort');
    }

    /**
     * Gets how the property will be sorted.
     *
     * @return null|string
     */
    public function getSortBy(): ?string
    {
        return $this->get('sortBy');
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
        $this->addStartEndValidation('filter.map_size');
        $this->addStartEndValidation('filter.guess_count');
        $this->addStartEndValidation('filter.max_teams');
        $this->addStartEndValidation('filter.min_players');
        $this->addStartEndValidation('filter.max_players');
        $this->addStartEndValidation('filter.created_at', true);

        // Run validation
        $this->validator->validate();
    }
}
