<?php

use App\Word;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */
$factory->define(Word::class, function (Faker $faker) {
    $word = null;
    $gen = $faker->unique(true);

    while (mb_strlen($word) > Word::WORD_MAX_LENGTH || $word === null) {
        switch (mt_rand(0, 9)) {
            case 0:
                $word = $gen->name;
                break;
            case 1:
                $word = $gen->domainWord;
                break;
            case 2:
                $word = $gen->citySuffix;
                break;
            case 3:
                $word = $gen->city;
                break;
            case 4:
                $word = $gen->streetName;
                break;
            case 5:
                $word = $gen->state;
                break;
            case 6:
                $word = $gen->country;
                break;
            case 7:
                $word = $gen->jobTitle;
                break;
            case 8:
                $word = $gen->company;
                break;
            case 9:
                $word = $gen->safeColorName;
        }
    }

    return [
        'word' => $word,
    ];
});
