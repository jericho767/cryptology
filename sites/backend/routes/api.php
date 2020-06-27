<?php

use App\Models\Permission;
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
            ->middleware('can:' . Permission::ALL['gameSettings.create'])
            ->name('create');

        // View
        Route::get('{gameSetting}', 'GameSettingController@show')
            ->middleware([
                'can:' . Permission::ALL['gameSettings.read'],
                'handle:gameSettings,gameSetting',
            ])
            ->name('view');

        // List
        Route::get('', 'GameSettingController@index')
            ->middleware('can:' . Permission::ALL['gameSettings.read'])
            ->name('index');

        // Update
        Route::put('{gameSetting}', 'GameSettingController@update')
            ->middleware([
                'can:' . Permission::ALL['gameSettings.update'],
                'handle:gameSettings,gameSetting',
            ])
            ->name('update');

        // Delete
        Route::delete('{gameSetting}', 'GameSettingController@delete')
            ->middleware([
                'can:' . Permission::ALL['gameSettings.delete'],
                'handle:gameSettings,gameSetting',
            ])
            ->name('delete');

        // Activate game setting
        Route::post('activate/{gameSetting}', 'GameSettingController@activate')
            ->middleware([
                'can:' . Permission::ALL['gameSettings.activate'],
                'handle:gameSettings,gameSetting',
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
                'can:' . Permission::ALL['roles.list']
            ])
            ->name('index');

        // Renew
        Route::post('renew/{player}', 'RoleController@renew')
            ->middleware([
                'can:' . Permission::ALL['roles.update'],
                'handle:roles,player',
            ])
            ->name('renew');

        // Create
        Route::post('create', 'RoleController@store')
            ->middleware([
                'can:' . Permission::ALL['roles.create'],
            ])
            ->name('create');

        // Update
        Route::put('{role}', 'RoleController@update')
            ->middleware([
                'can:' . Permission::ALL['roles.update'],
                'handle:roles,role',
            ])
            ->name('update');
    });

    /**
     * Permissions
     */
    Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
        // List
        Route::get('', 'PermissionController@index')
            ->middleware([
                'can:' . Permission::ALL['permissions.list'],
            ])
            ->name('index');
    });
});
