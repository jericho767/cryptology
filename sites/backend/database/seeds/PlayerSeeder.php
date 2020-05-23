<?php

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class PlayerSeeder
 */
class PlayerSeeder extends Seeder
{
    const SUPER_ADMIN_DATA = [
        'id' => 1,
        'name' => 'Jericho',
        'email' => 'boss.amo@power.com',
        'phone_number' => '09988451721',
    ];

    const ADMIN_DATA = [
        'id' => 2,
        'name' => 'Admin',
        'email' => 'admin@power.com',
        'phone_number' => '09988451721',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Add super admin user
        $superAdmin = new Player(self::SUPER_ADMIN_DATA + [
            'password' => Hash::make('sadministrator'),
            'email_verified_at' => Carbon::now(),
        ]);
        $superAdmin->save();

        // Add admin user
        $admin = new Player(self::ADMIN_DATA + [
            'password' => Hash::make('administrator'),
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->save();

        factory(Player::class, 80)->make()->each(function (Player $player): void {
            // Check if player with the same email address exists
            $hasExists = Player::query()
                ->where('email', $player->getAttribute('email'))
                ->exists();

            if (!$hasExists) {
                $player->save();
            }
        });
    }
}
