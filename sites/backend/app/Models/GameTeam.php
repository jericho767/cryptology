<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function gameWon(): HasOne
    {
        return $this->hasOne(Game::class, 'winner', 'id');
    }

    public function gameBlocks(): HasMany
    {
        return $this->hasMany(GameMap::class, 'game_team_id', 'id');
    }

    public function gameMaster(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'game_master', 'id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(GameTeamPlayer::class, 'game_team_id', 'id');
    }
}
