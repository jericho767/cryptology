<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameTurn extends BaseModel
{
    protected $table = 'game_turns';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['turn_order_id', 'guess_count', 'clue'];

    /**
     * @var int Maximum acceptable character length of the clue attribute
     */
    const CLUE_MAX_LENGTH = 50;

    public function guesses(): HasMany
    {
        return $this->hasMany(TurnGuess::class, 'game_turn_id', 'id');
    }

    public function turnOrder(): BelongsTo
    {
        return $this->belongsTo(TurnOrder::class, 'turn_order_id', 'id');
    }
}
