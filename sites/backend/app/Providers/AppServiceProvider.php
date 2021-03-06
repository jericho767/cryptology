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
        // Add custom validations
        $this->addValidations();
    }

    /**
     * Add user defined validations.
     *
     * @return void
     */
    private function addValidations()
    {
        $this->addMapGuessCountIntegrity();
    }

    /**
     * Validation for the integrity between map size and guess count.
     *
     * @return void
     */
    private function addMapGuessCountIntegrity(): void
    {
        $ruleCallback = function ($attribute, $value, $params, Validator $validator): bool {
            $maxTeams = intval($validator->getData()['max_teams']);
            $guessCount = intval($validator->getData()['guess_count']);
            $mapSize = intval($validator->getData()['map_size']);

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
