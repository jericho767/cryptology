<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerActivity extends Model
{
    protected $table = 'player_activities';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['player_id', 'login_date', 'logout_date'];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
