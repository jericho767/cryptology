<?php

namespace App;

use App\Models\BaseModel;
use Mockery\Exception;

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

    /**
     * Validates the model.
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function validate(): bool
    {
        $mapSize = $this->getAttribute('map_size');
        $guessCount = $this->getAttribute('guess_count');
        $maxTeams = $this->getAttribute('max_teams');
        $minPlayers = $this->getAttribute('min_players');
        $maxPlayers = $this->getAttribute('max_players');

        if ($guessCount < 1) {
            throw new Exception('Guess count cannot be zero.');
        }

        // 2 is the least number of teams to operate a game
        if ($maxTeams < 2) {
            throw new Exception('Team count must be at least 2.');
        }

        // 2 is the least number of players for a team to play (1 for guesser, 1 for game master)
        if ($minPlayers < 2 || $maxPlayers < 2) {
            throw new Exception('Player count needs to be at least 2.');
        }

        $roleBlocks = 0;
        // Additional blocks for each team
        for ($i = 0; $i < $maxTeams; $i++) {
            // $i - count of blocks for additional guess for each team
            $roleBlocks += $i;
        }

        // Number of assassins
        $assassinsCount = $maxTeams - 1;
        $roleBlocks += $guessCount * $maxTeams + $assassinsCount;

        if ($mapSize <= $roleBlocks) {
            throw new Exception('Cannot fit number of role blocks in the map.');
        }

        return true;
    }

    public static function boot(): void
    {
        parent::boot();

        $callback = function(GameSetting $model): void {
            $model->validate();
        };

        self::creating($callback);
        self::updating($callback);
    }
}
