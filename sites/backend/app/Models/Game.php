<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Game
 * @package App\Models
 */
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
     * RELATION for `GameMap` model
     *
     * @return HasMany
     */
    public function mapBlocks(): HasMany
    {
        return $this->hasMany(GameMap::class, 'game_id', 'id');
    }

    /**
     * RELATION for `GameTeam` model
     *
     * @return BelongsTo
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(GameTeam::class, 'winner', 'id');
    }

    /**
     * RELATION for `GameTeam` model
     *
     * @return HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(GameTeam::class, 'game_id', 'id');
    }

    /**
     * RELATION for `Player` model
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'created_by', 'id');
    }
}
