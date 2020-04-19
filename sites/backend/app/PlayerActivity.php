<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerActivity extends BaseModel
{
    protected $table = 'player_activities';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['player_id', 'login_date', 'logout_date'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
