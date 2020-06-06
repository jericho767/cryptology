<?php

namespace App\Http\Middleware;

use App\Models\GameSetting;
use App\Models\Player;
use App\Models\Role as Roles;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class HandleGameSetting
 * @package App\Http\Middleware
 */
class HandleGameSetting
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param GameSetting $gameSetting
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, GameSetting $gameSetting)
    {
        switch ($request->route()->getName()) {
            case 'game_settings.update':
            case 'game_settings.delete':
                /** @var Player $gameSettingCreator */
                $gameSettingCreator = Player::find($gameSetting->getAttribute('created_by'));

                if ($gameSettingCreator->hasRole(Roles::SUPER_ADMIN)) {
                    // Game setting is created by the all mighty, you can't touch this shit
                    throw new Exception(__('errors.permission.deny'));
                }
        }

        return $next($request);
    }
}
