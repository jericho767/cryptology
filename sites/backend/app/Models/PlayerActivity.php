<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PlayerActivity
 * @package App\Models
 */
class PlayerActivity extends BaseModel
{
    protected $table = 'player_activities';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['player_id', 'login_date', 'logout_date'];

    /**
     * RELATION for `Player` model
     *
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
