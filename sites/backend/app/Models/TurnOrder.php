<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TurnOrder
 * @package App\Models
 */
class TurnOrder extends BaseModel
{
    protected $table = 'turn_orders';
    protected $primaryKey = ['game_team_player_id'];
    public $incrementing = false;
    protected $fillable = ['game_team_player_id', 'has_played'];

    /**
     * int Value of the column `has_played` for orders that are not played
     */
    const HAS_NOT_PLAYED = 0;
    /**
     * int Value of the column `has_played` for orders that been played
     */
    const HAS_PLAYED = 1;
    /**
     * int Value of the column `has_played` for orders that are cannot play anymore (assassin has been guessed)
     */
    const CANNOT_PLAY_ANYMORE = 2;

    /**
     * RELATION for `GameTurn` model
     *
     * @return HasMany
     */
    public function turns(): HasMany
    {
        return $this->hasMany(GameTurn::class, 'turn_order_id', 'id');
    }

    /**
     * RELATION for `GameTeamPlayer` model
     *
     * @return BelongsTo
     */
    public function gameTeamPlayer(): BelongsTo
    {
        return $this->belongsTo(GameTeamPlayer::class, 'game_team_player_id', 'id');
    }
}
