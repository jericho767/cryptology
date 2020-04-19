<?php

use App\Player;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Hash;

/* @var Factory $factory */
$factory->define(Player::class, function (Faker $faker) {
    $phoneNumber = '09' . mt_rand(100000000, 999999999);

    return [
        'name' => $faker->unique(true)->firstName,
        'email' => $faker->unique(true)->email,
        'phone_number' => $phoneNumber,
        'password' => Hash::make('password'),
    ];
});
