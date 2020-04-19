<?php

use App\PlayerActivity;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */
$factory->define(PlayerActivity::class, function (Faker $faker): array {
    $isActive = collect([true, false, false])->random();

    if ($isActive) {
        // Since it's an active session, logout date should be NULL
        $logoutDate = null;

        // Set login date as (at most) 2hrs ago
        $loginDate = new Carbon($faker->dateTimeBetween('-2 hours', 'now'));
    } else {
        // Let's create a past login date
        $loginDate = new Carbon($faker->dateTimeBetween('-10 days', '-2 hours'));
        // Let's set the logout date to be 1 minute to 60 minutes from the login date
        $logoutDate = $loginDate->clone()->addMinutes(mt_rand(1, 60));
    }

    return [
        'login_date' => $loginDate,
        'logout_date' => $logoutDate,
    ];
});
