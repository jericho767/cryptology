<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Player
 * @package App\Models
 */
class Player extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

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

    /**
     * RELATION for `Game` model
     *
     * @return HasMany
     */
    public function gamesCreated(): HasMany
    {
        return $this->hasMany(Game::class, 'created_by', 'id');
    }

    /**
     * RELATION for `GameTeamPlayer` model
     *
     * @return HasMany
     */
    public function teams(): HasMany
    {
        return $this->hasMany(GameTeamPlayer::class, 'player_id', 'id');
    }

    /**
     * RELATION for `PlayerActivity` model
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(PlayerActivity::class, 'player_id', 'id');
    }

    /**
     * RELATION for `GameTeam` model
     *
     * @return HasMany
     */
    public function gameMasterOfGames(): HasMany
    {
        return $this->hasMany(GameTeam::class, 'game_master', 'id');
    }
}
