<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSetting as GameSettingRequest;
use App\Models\GameSetting;
use App\Http\Resources\GameSetting as GameSettingResource;
use App\Services\GameSettingService;

/**
 * Class GameSettingController
 * @package App\Http\Controllers
 */
class GameSettingController extends Controller
{
    private $gameSettingService;

    /**
     * GameSettingController constructor.
     * @param GameSettingService $gameSettingService
     */
    public function __construct(GameSettingService $gameSettingService)
    {
        parent::__construct();
        $this->gameSettingService = $gameSettingService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param GameSettingRequest $request
     * @return GameSettingResource
     */
    public function store(GameSettingRequest $request): GameSettingResource
    {
        $data = [
            'map_size' => $request->getMapSize(),
            'guess_count' => $request->getGuessCount(),
            'max_teams' => $request->getMaxTeams(),
            'min_players' => $request->getMinPlayers(),
            'max_players' => $request->getMaxPlayers(),
            'is_active' => $request->getIsActive(),
            'created_by' => $this->user->getAttribute('id'),
        ];

        return new GameSettingResource($this->gameSettingService->create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param GameSetting $gameSetting
     * @return GameSettingResource
     */
    public function show(GameSetting $gameSetting): GameSettingResource
    {
        $gameSetting->load('createdBy');
        return new GameSettingResource($gameSetting);
    }
}
