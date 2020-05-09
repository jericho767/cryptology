<?php

use App\Models\Player;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        // List of permissions
        $permissions = [
            // Word model
            'create words',
            'read words',
            'update words',
            'delete words',
            'search words',
            // GameSetting model
            'create game settings',
            'read game settings',
            'update game settings',
            'delete game settings',
            'search game settings',
            // Player model
            'create players',
            'read players',
            'update players',
            'delete players',
            'search players',
            // PlayerActivity model
            'read player activities',
            // Game model
            'create games',
            'read games',
            'update games',
            'delete games',
            'search games',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create the super admin role
        $superAdminRole = Role::create(['name' => 'super-admin']);
        // Create admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Add permissions to the admin role
        $adminRole->givePermissionTo([
            'create words',
            'read words',
            'search words',
            'read game settings',
            'read players',
            'search players',
            'read games',
            'search games',
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
