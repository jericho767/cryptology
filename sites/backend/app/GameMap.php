<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMap extends BaseModel
{
    protected $table = 'game_maps';
    protected $primaryKey = ['game_id', 'word_id'];
    public $incrementing = false;
    protected $fillable = ['game_id', 'word_id', 'block_number', 'block_owner', 'game_team_id'];

    /**
     * Ideal to be a perfect square.
     *
     * @var int Number of blocks in a game map
     */
    const MAP_SIZE = 25;
    /**
     * @var int Number of blocks that will be guessed. First team to guess will have additional guess(es).
     */
    const GUESS_SIZE = 8;
    /**
     * @var int Number of words that will be added to be guessed by the first team to guess
     */
    const FIRST_TURN_ADD = 1;
    /**
     * @var int Number of assassin in the map
     */
    const NUM_OF_ASSASSIN = 1;
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

    public function guessAt(): HasOne
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
