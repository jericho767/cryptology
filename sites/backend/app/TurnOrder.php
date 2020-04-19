<?php

namespace App;

use App\Models\BaseModel;

class TurnOrder extends BaseModel
{
    protected $table = 'turn_orders';
    protected $primaryKey = ['game_team_player_id'];
    public $incrementing = false;
    protected $fillable = ['game_team_player_id', 'has_played'];

    public function turns()
    {
        return $this->hasMany(GameTurn::class, 'turn_order_id', 'id');
    }

    public function gameTeamPlayer()
    {
        return $this->belongsTo(GameTeamPlayer::class, 'game_team_player_id', 'id');
    }
}
