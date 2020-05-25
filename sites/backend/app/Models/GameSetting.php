<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class GameSetting
 * @package App\Models
 */
class GameSetting extends BaseModel
{
    protected $table = 'game_settings';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'map_size', // The number of blocks in a game. Preferably a perfect square number.
        'guess_count', // Minimum umber of blocks that needs to be guessed by a team
        'max_teams', // Maximum number of teams that can play in a game
        'min_players', // Minimum number of players that a team can have in order to play
        'max_players', // Maximum number of players that a team can have in order to play
    ];

    /**
     * `is_active` identifier that tells that the `game_setting` object is active
     *
     * @var int
     */
    const IS_ACTIVE = 1;

    /**
     * `is_active` identifier that tells that the `game_setting` object is NOT active
     */
    const IS_NOT_ACTIVE = 0;

    /**
     * Allowed maximum size of the map
     *
     * @var int
     */
    const ALLOWED_MAX_MAP_SIZE = 49;

    /**
     * Allowed minimum size of the map
     *
     * @var int
     */
    const ALLOWED_MIN_MAP_SIZE = 16;

    /**
     * Allowed minimum number of players per team to play
     *
     * @var int
     */
    const ALLOWED_MIN_PLAYERS = 2;

    /**
     * Allowed maximum number of players per team to play
     *
     * @var int
     */
    const ALLOWED_MAX_PLAYERS = 7;

    /**
     * Allowed maximum number of teams to play in a game
     *
     * @var int
     */
    const ALLOWED_MAX_TEAMS = 4;

    /**
     * Allowed minimum number of teams to play in a game
     *
     * @var int
     */
    const ALLOWED_MIN_TEAMS = 2;

    /**
     * RELATION for `Player` model
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'created_by', 'id');
    }
}
