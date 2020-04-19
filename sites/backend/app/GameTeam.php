<?php

namespace App;

use App\Models\BaseModel;

class GameTeam extends BaseModel
{
    protected $table = 'game_teams';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['game_id', 'team_name', 'game_master'];

    /**
     * @var int Maximum acceptable length of the `team_name`
     */
    const TEAM_NAME_MAX_LENGTH = 20;

    public function gameWon()
    {
        return $this->hasOne(Game::class, 'winner', 'id');
    }

    public function gameBlocks()
    {
        return $this->hasMany(GameMap::class, 'game_team_id', 'id');
    }

    public function gameMaster()
    {
        return $this->belongsTo(Player::class, 'game_master', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function players()
    {
        return $this->hasMany(GameTeamPlayer::class, 'game_team_id', 'id');
    }
}
