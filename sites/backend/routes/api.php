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

        // List
        Route::get('', 'GameSettingController@index')
            ->middleware('can:' . Permissions::ALL['gameSettings.read'])
            ->name('index');

        // Update
        Route::put('{gameSetting}', 'GameSettingController@update')
            ->middleware([
                'can:' . Permissions::ALL['gameSettings.update'],
                'handle:gameSetting,gameSetting',
            ])
            ->name('update');

        // Delete
        Route::delete('{gameSetting}', 'GameSettingController@delete')
            ->middleware([
                'can:' . Permissions::ALL['gameSettings.delete'],
                'handle:gameSetting,gameSetting',
            ])
            ->name('delete');

        // Activate game setting
        Route::post('activate/{gameSetting}', 'GameSettingController@activate')
            ->middleware([
                'can:' . Permissions::ALL['gameSettings.activate'],
                'handle:gameSetting,gameSetting',
            ])
            ->name('activate');
    });

    /**
     * Roles
     */
    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
        // List
        Route::get('', 'RoleController@index')
            ->middleware([
                'can:' . Permissions::ALL['roles.list']
            ])
            ->name('index');

        // Renew
        Route::post('renew/{player}', 'RoleController@renew')
            ->middleware([
                'can:' . Permissions::ALL['roles.update'],
                'loginUserAndRoleAction',
            ])
            ->name('renew');
    });
});
