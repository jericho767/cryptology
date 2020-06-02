<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSetting as GameSettingRequest;
use App\Http\Resources\GameSettingCollection;
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
     * Display a listing of the resource.
     *
     * @param GameSettingRequest $request
     * @return GameSettingCollection
     */
    public function index(GameSettingRequest $request): GameSettingCollection
    {
        return new GameSettingCollection(
            $this->gameSettingService->all(
                $request->getLimit(),
                $request->getFilters(),
                $request->getSort(),
                $request->getSortBy()
            )
        );
    }

    /**
     * Creates a new resource.
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

    /**
     * Updates a resource.
     *
     * @param GameSettingRequest $request
     * @param GameSetting $gameSetting
     * @return GameSettingResource
     */
    public function update(GameSettingRequest $request, GameSetting $gameSetting): GameSettingResource
    {
        $data = [
            'map_size' => $request->getMapSize(),
            'guess_count' => $request->getGuessCount(),
            'max_teams' => $request->getMaxTeams(),
            'min_players' => $request->getMinPlayers(),
            'max_players' => $request->getMaxPlayers(),
            'is_active' => $request->getIsActive(),
        ];

        return new GameSettingResource($this->gameSettingService->update($data, $gameSetting));
    }
}
