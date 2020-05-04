<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class GameTeam
 * @package App\Models
 */
class GameTeam extends BaseModel
{
    protected $table = 'game_teams';
    protected $primaryKey = ['game_id', 'game_master'];
    public $incrementing = false;
    protected $fillable = ['game_id', 'team_name', 'game_master'];

    /**
     * @var int Maximum acceptable length of the `team_name`
     */
    const TEAM_NAME_MAX_LENGTH = 20;

    /**
     * RELATION for `Game` model
     *
     * @return HasOne
     */
    public function gameWon(): HasOne
    {
        return $this->hasOne(Game::class, 'winner', 'id');
    }

    /**
     * RELATION for `GameMap` model
     *
     * @return HasMany
     */
    public function gameBlocks(): HasMany
    {
        return $this->hasMany(GameMap::class, 'game_team_id', 'id');
    }

    /**
     * RELATION for `Player` model
     *
     * @return BelongsTo
     */
    public function gameMaster(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'game_master', 'id');
    }

    /**
     * RELATION for `Game` model
     *
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    /**
     * RELATION for `GameTeamPlayer` model
     *
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(GameTeamPlayer::class, 'game_team_id', 'id');
    }
}
