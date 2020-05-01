<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameTeamPlayer extends BaseModel
{
    protected $table = 'game_team_players';
    protected $primaryKey = ['game_team_id', 'player_id'];
    public $incrementing = false;
    protected $fillable = ['game_team_id', 'player_id'];

    public function gameTeam(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id', 'id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function turnOrder(): HasOne
    {
        return $this->hasOne(TurnOrder::class, 'game_team_player_id', 'id');
    }
}
