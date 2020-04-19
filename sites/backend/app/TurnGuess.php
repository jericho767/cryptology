<?php

namespace App;

use App\Models\BaseModel;

class TurnGuess extends BaseModel
{
    protected $table = 'turn_guesses';
    protected $primaryKey = ['game_turn_id', 'game_map_id'];
    public $incrementing = false;
    protected $fillable = ['game_turn_id', 'game_map_id'];

    public function gameMap()
    {
        return $this->belongsTo(GameMap::class, 'game_map_id', 'id');
    }

    public function gameTurn()
    {
        return $this->belongsTo(GameTurn::class, 'game_turn_id', 'id');
    }
}
