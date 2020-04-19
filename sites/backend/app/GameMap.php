<?php

namespace App;

use App\Models\BaseModel;

class GameMap extends BaseModel
{
    protected $table = 'game_maps';
    protected $primaryKey = ['game_id', 'word_id'];
    public $incrementing = false;
    protected $fillable = ['game_id', 'word_id', 'block_number', 'block_owner', 'game_team_id'];

    /**
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

    public function word()
    {
        return $this->belongsTo(Word::class, 'word_id', 'id');
    }

    public function guessAt()
    {
        // TODO Add relation
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function blockOwner()
    {
        // TODO Add relation
    }
}
