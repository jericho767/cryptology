<?php

namespace App\Services;

use App\Models\GameSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class GameSettingService
 * @package App\Services
 */
class GameSettingService extends BaseService
{
    /**
     * Current game setting
     *
     * @var GameSetting $currentGameSetting
     */
    private $currentGameSetting;

    public function __construct()
    {
        parent::__construct();
        $this->currentGameSetting = GameSetting::query()
            ->orderBy('id', 'desc')
            ->where('is_active', GameSetting::IS_ACTIVE)
            ->first();

        if ($this->currentGameSetting === null) {
            // Fallback game settings, fetch the latest added game setting
            $this->currentGameSetting = GameSetting::query()
                ->orderBy('id', 'desc')
                ->first();
        }
    }

    /**
     * Gets all entries of game settings base on the filters and sort
     *
     * @param array $filters
     * @param string|null $sort
     * @param string|null $sortBy
     * @return Collection
     */
    public function all(array $filters, ?string $sort, ?string $sortBy): Collection
    {
        // Initialize query
        $query = GameSetting::query();

        // Iterate all given filters
        foreach ($filters as $filter => $params) {
            // Fetch the column to be filtered base on the request field name
            $column = GameSetting::getColumnByFilterField($filter);

            // This is where the filtering happens, it's self-explanatory
            switch ($column) {
                case 'created_by':
                    if ($params !== null && is_array($params)) {
                        $query->whereIn($column, $params);
                    }
                    break;
                case 'map_size':
                case 'guess_count':
                case 'max_teams':
                case 'min_players':
                case 'max_players':
                case 'created_at':
                    $start = $params['start'];
                    $end = $params['end'];

                    if ($start !== null && $end !== null) {
                        // Both start and end are set, use between clause
                        $query->whereBetween($column, [$start, $end]);
                    } elseif ($start === null && $end !== null) {
                        // Only the end part is set
                        if ($end instanceof Carbon) {
                            $query->whereDate($column, '<=', $end);
                        } else {
                            $query->where($column, '<=', $end);
                        }
                    } elseif ($start !== null && $end === null) {
                        // Only the start part is set
                        if ($start instanceof Carbon) {
                            $query->whereDate($column, '>=', $start);
                        } else {
                            $query->where($column, '>=', $start);
                        }
                    }
            }
        }

        // Apply sorting
        if ($sort !== null && $sortBy !== null) {
            $query->orderBy(GameSetting::getColumnBySortField($sortBy), $sort);
        }

        return $query->get();
    }

    /**
     * Creates a `game_settings` entry
     *
     * @param array $data
     * @return null|GameSetting
     */
    public function create(array $data): ?GameSetting
    {
        $gameSetting = null;

        DB::transaction(function () use ($data, &$gameSetting) {
            // Newly inserted is set to be an active game setting
            if (isset($data['is_active']) && $data['is_active'] === GameSetting::IS_ACTIVE) {
                // Deactivate all existing game settings
                $this->deactivateAll();
            }

            $gameSetting = GameSetting::create($data);
        });

        return $gameSetting;
    }

    /**
     * Deactivates all existing `game_settings`
     *
     * @return void
     */
    private function deactivateAll(): void
    {
        DB::transaction(function () {
            GameSetting::query()
                ->where('is_active', GameSetting::IS_ACTIVE)
                ->update(['is_active' => GameSetting::IS_NOT_ACTIVE]);
        });
    }

    /**
     * Gets the current map size setting
     *
     * @return int
     */
    public function getMapSize(): int
    {
        return $this->currentGameSetting->getAttribute('map_size');
    }

    /**
     * Gets the current setting for guess count
     *
     * @return int
     */
    public function getGuessCount(): int
    {
        return $this->currentGameSetting->getAttribute('guess_count');
    }

    /**
     * Gets the current setting for maximum number of teams that can participate in a game
     *
     * @return int
     */
    public function getMaxTeams(): int
    {
        return $this->currentGameSetting->getAttribute('max_teams');
    }

    /**
     * Gets the current setting for minimum number of players in a team in order to participate
     *
     * @return int
     */
    public function getMinPlayers(): int
    {
        return $this->currentGameSetting->getAttribute('min_players');
    }

    /**
     * Gets the current setting for maximum number of players in a team in order to participate
     *
     * @return int
     */
    public function getMaxPlayers(): int
    {
        return $this->currentGameSetting->getAttribute('max_players');
    }
}
