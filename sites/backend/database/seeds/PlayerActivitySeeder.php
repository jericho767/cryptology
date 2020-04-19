<?php

use App\Player;
use App\PlayerActivity;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;

class PlayerActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $players = Player::all()->shuffle();

        factory(PlayerActivity::class, 100)->make()->each(
            function (PlayerActivity $activity) use ($players): void {
                if ($activity->getAttribute('logout_date') !== null) {
                    /*
                     * It's an inactive activity
                     * Assign it to any player (doesn't matter).
                     *
                     * It'll be possible that a player can have an overlapping activities.
                     * (Activities with the overlapping login time)
                     * It's due to logging into multiple devices.
                     */
                    /** @var Player $player */
                    $player = $players->random();
                } else {
                    // It's an active activity, fetch all inactive players and make them active
                    $inactivePlayers = Player::query()->whereHas('activities', function ($query): void {
                        /* @var Builder $query */
                        $query->whereNull('logout_date');
                    }, '<', 1)->get()->shuffle();

                    /* @var Player $player */
                    if ($inactivePlayers->count() > 0) {
                        $player = $inactivePlayers->random();
                    } else {
                        // Since all users are active, assign this active activity randomly then
                        $player = $players->random();
                    }
                }

                $activity->setAttribute('player_id', $player->getAttribute('id'));
                $activity->save();
            }
        );
    }
}
