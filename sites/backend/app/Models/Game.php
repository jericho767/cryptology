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
