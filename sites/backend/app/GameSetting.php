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

    /**
     * Validates the model.
     *
     * @return string|null
     */
    protected function validate(): ?string
    {
        $mapSize = $this->getAttribute('map_size');
        $guessCount = $this->getAttribute('guess_count');
        $maxTeams = $this->getAttribute('max_teams');
        $minPlayers = $this->getAttribute('min_players');
        $maxPlayers = $this->getAttribute('max_players');

        if ($guessCount < 1) {
            return 'Guess count cannot be zero.';
        }

        // 2 is the least number of teams to operate a game
        if ($maxTeams < 2) {
            return 'Team count must be at least 2.';
        }

        // 2 is the least number of players for a team to play (1 for guesser, 1 for game master)
        if ($minPlayers < 2 || $maxPlayers < 2) {
            return 'Player count needs to be at least 2.';
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
            return 'Cannot fix number of role blocks in the map.';
        }

        return null;
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function(GameSetting $model): bool {
            return $model->validate() === null;
        });

        self::updating(function(GameSetting $model): bool {
            return $model->validate() === null;
        });
    }
}
