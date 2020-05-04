<?php

namespace App\Services;

use App\Models\GameSetting;

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
            ->first();
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
