<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMap extends BaseModel
{
    protected $table = 'game_maps';
    protected $primaryKey = ['game_id', 'word_id'];
    public $incrementing = false;
    protected $fillable = ['game_id', 'word_id', 'block_number', 'block_owner', 'game_team_id'];

    /**
     * @var int `block_owner` constant indicating the blocks belongs to an assassin
     */
    const ASSASSIN_BLOCK_NUM = 0;
    /**
     * @var int `block_owner` constant indicating the blocks belongs to a civilian
     */
    const CIVILIAN_BLOCK_NUM = 1;
    /**
     * @var int `block_owner` constant indicating the blocks belongs to a team
     */
    const TEAM_BLOCK_NUM = 2;

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class, 'word_id', 'id');
    }

    public function guessedAt(): HasOne
    {
        return $this->hasOne(TurnGuess::class, 'game_map_id', 'id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function blockOwner(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id', 'id');
    }
}
