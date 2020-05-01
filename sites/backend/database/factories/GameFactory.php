<?php

use App\Models\Game;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */
$factory->define(Game::class, function (): array {
    // Choice of characters to form the game name
    $acceptedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_-';
    $chars = collect(str_split($acceptedChars, 1))->shuffle();

    // Minimum length of game name
    $nameMinLength = 7;

    return [
        'name' => implode('', $chars->random(mt_rand($nameMinLength, Game::NAME_MAX_LENGTH))->toArray()),
    ];
});
