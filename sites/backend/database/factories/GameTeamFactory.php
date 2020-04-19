<?php

use App\GameTeam;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(GameTeam::class, function (Faker $faker): array {
    return [
        'team_name' => $faker->realText(GameTeam::TEAM_NAME_MAX_LENGTH),
    ];
});
