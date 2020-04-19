<?php

namespace App;

use App\Models\BaseModel;

class GameTurn extends BaseModel
{
    protected $table = 'game_turns';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['turn_order_id', 'guess_count', 'clue'];

    public function guesses()
    {
        // TODO Define
    }

    public function turnOrder()
    {
        return $this->belongsTo(TurnOrder::class, 'turn_order_id', 'id');
    }
}
