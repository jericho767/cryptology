<?php

namespace App\Services;

use App\Models\GameSetting;

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
     * Gets the current map size settings
     *
     * @return int
     */
    public function getMapSize(): int
    {
        return $this->currentGameSetting->getAttribute('map_size');
    }
}
