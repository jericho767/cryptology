<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends BaseModel
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['name', 'winner', 'created_by'];

    /**
     * @var int Maximum acceptable length of the name attribute
     */
    const NAME_MAX_LENGTH = 20;
    /**
     * @var int Number of teams to be playing in a game
     */
    const MAX_NUMBER_OF_PLAYING_TEAMS = 2;
    /**
     * @var int Minimum number of players per team in order to play
     */
    const MIN_PLAYERS_PER_TEAM = 2;

    public function mapBlocks(): HasMany
    {
        return $this->hasMany(GameMap::class, 'game_id', 'id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'winner', 'id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(GameTeam::class, 'game_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'created_by', 'id');
    }
}
