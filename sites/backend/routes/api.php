<?php

use App\Models\Permission as Permissions;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api', 'accept.json']], function () {
    /**
     * Game Settings
     */
    Route::group(['prefix' => 'game-settings', 'as' => 'game_settings.'], function () {
        // Create
        Route::post('', 'GameSettingController@store')
            ->middleware('can:' . Permissions::ALL['gameSettings.create'])
            ->name('create');

        // View
        Route::get('{gameSetting}', 'GameSettingController@show')
            ->middleware([
                'can:' . Permissions::ALL['gameSettings.read'],
                'handle:gameSetting,gameSetting',
            ])
            ->name('view');
    });
});
