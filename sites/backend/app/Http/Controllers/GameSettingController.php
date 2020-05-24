<?php

namespace App\Http\Controllers;

use App\Models\GameSetting;
use App\Http\Resources\GameSetting as GameSettingResource;

/**
 * Class GameSettingController
 * @package App\Http\Controllers
 */
class GameSettingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param GameSetting $gameSetting
     * @return GameSettingResource
     */
    public function show(GameSetting $gameSetting)
    {
        $gameSetting->load('createdBy');
        return new GameSettingResource($gameSetting);
    }
}
