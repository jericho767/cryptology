<?php

namespace App\Models;

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
     * NOTE: This is not easily adjustable, changing this value
     *       will entail a change in other parts as well, like:
     *       MAP_SIZE, to accommodate the growing number of
     *                 participating teams
     *
     *       AND the addition of blocks to be guessed must also
     *           be adjusted in order for it to be a fair game.
     *
     *           Scenario: Set max teams to three(3)
     *           First team needs to guess `n+1` (for +1, check GAME_MAP::FIRST_TURN_ADD)
     *           Second team needs to guess `n`
     *           Third team needs to guess `n` as well.(unjust with second team)
     *
     *       AND the number of assassins must also be adjusted
     *           to the number of participating teams
     *************************************************
     * At the time of development it was casted that the game
     * WILL handle more than 2 teams. But it was just a plan,
     * a fevered dream of a young man who's been dismissed as
     * foolish and ambitious.
     *
     * @var int Number of teams to be playing in a game
     */
    const MAX_NUMBER_OF_PLAYING_TEAMS = 2;
    /**
     * @var int Minimum number of players per team in order to play (including game master)
     */
    const MIN_PLAYERS_PER_TEAM = 2;
    /**
     * @var int Maximum number of players per team that can play (including game master)
     */
    const MAX_PLAYERS_PER_TEAM = 6;

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
