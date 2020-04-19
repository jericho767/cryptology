<?php

namespace App;

use App\Models\BaseModel;

class GameTeamPlayer extends BaseModel
{
    protected $table = 'game_team_players';
    protected $primaryKey = ['game_team_id', 'player_id'];
    public $incrementing = false;
    protected $fillable = ['game_team_id', 'player_id'];

    public function gameTeam()
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id', 'id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function turnOrder()
    {
        return $this->hasOne(TurnOrder::class, 'game_team_player_id', 'id');
    }
}
