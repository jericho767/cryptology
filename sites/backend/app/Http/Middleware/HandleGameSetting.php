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
        /** @var Player $player */
        $player = $request->user();

        switch ($request->route()->getName()) {
            case 'game_settings.create':
            case 'game_settings.view':
            case 'game_settings.viewAll':
                if (!$player->hasRole(Roles::ALL['admin'])) {
                    throw new Exception('You just cant cunt.');
                }
                break;
            case 'game_settings.update':
            case 'game_settings.delete':
                /** @var Player $gameSettingCreator */
                $gameSettingCreator = Player::find($gameSetting->getAttribute('created_by'));
                if ($gameSettingCreator->hasRole(Roles::ALL['super.admin'])) {
                    // Game setting is created by the all mighty, you can't touch this shit
                    throw new Exception('You can\'t touch this shit.');
                }
        }

        return $next($request);
    }
}
