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
    const PERMISSIONS = [
        // Word model
        'words.create' => 'create words',
        'words.read' => 'read words',
        'words.update' => 'update words',
        'words.delete' => 'delete words',
        'words.search' => 'search words',
        // GameSetting model
        'gameSettings.create' => 'create game settings',
        'gameSettings.read' => 'read game settings',
        'gameSettings.update' => 'update game settings',
        'gameSettings.delete' => 'delete game settings',
        'gameSettings.search' => 'search game settings',
        // Player model
        'players.create' => 'create players',
        'players.read' => 'read players',
        'players.update' => 'update players',
        'players.delete' => 'delete players',
        'players.search' => 'search players',
        // PlayerActivity model
        'playerActivities.read' => 'read player activities',
        // Game model
        'games.create' => 'create games',
        'games.read' => 'read games',
        'games.update' => 'update games',
        'games.delete' => 'delete games',
        'games.search' => 'search games',
    ];
}
