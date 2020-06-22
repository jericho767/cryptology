<?php

use App\Models\Permission;
use App\Models\Player;
use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * Class RoleSeeder
 */
class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        foreach (Permission::ALL as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create the super admin role
        $superAdminRole = Role::create(['name' => Role::SUPER_ADMIN]);
        // Create admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Add permissions to the admin role
        $adminRole->givePermissionTo([
            Permission::ALL['words.create'],
            Permission::ALL['words.read'],
            Permission::ALL['words.search'],
            Permission::ALL['gameSettings.read'],
            Permission::ALL['players.read'],
            Permission::ALL['players.search'],
            Permission::ALL['games.read'],
            Permission::ALL['games.search'],
        ]);

        // Assign roles to users
        /** @var Player $superAdminPlayer */
        $superAdminPlayer = Player::query()->find(PlayerSeeder::SUPER_ADMIN_DATA['id']);
        $superAdminPlayer->assignRole($superAdminRole);

        /** @var Player $adminPlayer */
        $adminPlayer = Player::query()->find(PlayerSeeder::ADMIN_DATA['id']);
        $adminPlayer->assignRole($adminRole);
    }
}
