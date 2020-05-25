<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator as Validation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $ruleCallback = function ($attribute, $value, $params, Validator $validator): bool
        {
            $maxTeams = $validator->getData()['max_teams'];
            $guessCount = $validator->getData()['guess_count'];
            $mapSize = $validator->getData()['map_size'];

            // Blocks that have roles(assassin or for a team)
            $roleBlocks = 0;

            // Additional blocks for each team
            for ($i = 0; $i < $maxTeams; $i++) {
                /*
                 * $i - count of blocks for additional guess for each team
                 * 0 - added for the 1st team
                 * 1 - for the 2nd
                 * 2 - for the 3rd
                 * and so forth
                 */
                $roleBlocks += $i;
            }

            // Number of assassins
            $assassinsCount = $maxTeams - 1;

            // Total role blocks
            $roleBlocks += $guessCount * $maxTeams + $assassinsCount;

            // Map size cannot be less than or equal, there'll be no more room for non-role block(civilians)
            if ($mapSize <= $roleBlocks) {
                return false;
            }

            return true;
        };
        Validation::extend('guess_count_integrity', $ruleCallback);
        Validation::extend('map_size_integrity', $ruleCallback);
    }
}
