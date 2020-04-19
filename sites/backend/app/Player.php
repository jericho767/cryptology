<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends BaseModel
{
    protected $table = 'players';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['name', 'email', 'phone_number', 'password'];

    /**
     * @var int Maximum acceptable length of the name attribute
     */
    const NAME_MAX_LENGTH = 20;
    /**
     * @var int Maximum acceptable length of the email attribute
     */
    const EMAIL_MAX_LENGTH = 100;
    /**
     * @var int Maximum acceptable length of the phone number attribute
     */
    const PHONE_NUMBER_MAX_LENGTH = 11;

    public function gamesCreated(): HasMany
    {
        return $this->hasMany(Game::class, 'created_by', 'id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(GameTeamPlayer::class, 'player_id', 'id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(PlayerActivity::class, 'player_id', 'id');
    }

    public function gameMasterOfGames(): HasMany
    {
        return $this->hasMany(GameTeam::class, 'game_master', 'id');
    }
}
