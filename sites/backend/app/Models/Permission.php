<?php

namespace App\Models;

/**
 * This class only holds the constant permissions in `permissions` table.
 * For the actual Permission model, it is implemented by a package.
 *
 * @package App\Models
 */
class Permission
{
    /**
     * List of permissions
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
    ];
}
