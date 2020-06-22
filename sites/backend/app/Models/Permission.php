<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends SpatiePermission
{
    /**
     * List of permissions.
     * Once updated don't forget to run:
     *      php artisan permissions:update
     *
     * To reflect changes to the database.
     *
     * @var array
     */
    const ALL = [
        // Word model
        'words.create' => 'words.create',
        'words.read' => 'words.read',
        'words.update' => 'words.update',
        'words.delete' => 'words.delete',
        'words.search' => 'words.search',
        // GameSetting model
        'gameSettings.create' => 'gameSettings.create',
        'gameSettings.read' => 'gameSettings.read',
        'gameSettings.update' => 'gameSettings.update',
        'gameSettings.delete' => 'gameSettings.delete',
        'gameSettings.search' => 'gameSettings.search',
        'gameSettings.activate' => 'gameSettings.activate',
        // Player model
        'players.create' => 'players.create',
        'players.read' => 'players.read',
        'players.update' => 'players.update',
        'players.delete' => 'players.delete',
        'players.search' => 'players.search',
        // PlayerActivity model
        'playerActivities.read' => 'playerActivities.read',
        // Game model
        'games.create' => 'games.create',
        'games.read' => 'games.read',
        'games.update' => 'games.update',
        'games.delete' => 'games.delete',
        'games.search' => 'games.search',
        // Role model
        'roles.update' => 'roles.update',
        'roles.list' => 'roles.list',
        'roles.create' => 'roles.create',
    ];
}
