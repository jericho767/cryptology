<?php

namespace App;

use App\Models\BaseModel;

class GameSetting extends BaseModel
{
    protected $table = 'game_settings';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'map_size', // The number of blocks in a game. Preferably a perfect square number.
        'guess_count', // Minimum umber of blocks that needs to be guessed by a team
        'max_teams', // Maximum number of teams that can play in a game
        'min_players', // Minimum number of players that a team can have in order to play
        'max_players', // Maximum number of players that a team can have in order to play
    ];
}
