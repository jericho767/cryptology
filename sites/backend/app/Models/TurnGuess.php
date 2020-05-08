<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TurnGuess
 * @package App\Models
 */
class TurnGuess extends BaseModel
{
    protected $table = 'turn_guesses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['game_turn_id', 'game_map_id'];

    /**
     * RELATION for `GameMap` model
     *
     * @return BelongsTo
     */
    public function gameMap(): BelongsTo
    {
        return $this->belongsTo(GameMap::class, 'game_map_id', 'id');
    }

    /**
     * RELATION for `GameTurn` model
     *
     * @return BelongsTo
     */
    public function gameTurn(): BelongsTo
    {
        return $this->belongsTo(GameTurn::class, 'game_turn_id', 'id');
    }
}
