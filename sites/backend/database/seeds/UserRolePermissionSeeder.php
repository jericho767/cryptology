<?php

use App\Models\Permission as Permissions;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as Roles;

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
        foreach (Permissions::ALL as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create the super admin role
        $superAdminRole = Role::create(['name' => Roles::SUPER_ADMIN]);
        // Create admin role
        $adminRole = Role::create(['name' => Roles::ADMIN]);

        // Add permissions to the admin role
        $adminRole->givePermissionTo([
            Permissions::ALL['words.create'],
            Permissions::ALL['words.read'],
            Permissions::ALL['words.search'],
            Permissions::ALL['gameSettings.read'],
            Permissions::ALL['players.read'],
            Permissions::ALL['players.search'],
            Permissions::ALL['games.read'],
            Permissions::ALL['games.search'],
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
