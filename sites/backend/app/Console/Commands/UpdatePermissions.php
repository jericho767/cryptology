<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Class UpdatePermissions
 * @package App\Console\Commands
 */
class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the list of permissions base on Permission::ALL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $flushFlag = false;

        $createdPermissions = $this->createPermissions();
        $deletedPermissions = $this->deletePermissions();

        if (count($createdPermissions) > 0) {
            $flushFlag = true;
            echo "++++++++++++++++++++++++++\n";
            echo "CREATED PERMISSIONS\n";
            echo "++++++++++++++++++++++++++\n";
            echo implode("\n", $createdPermissions) . "\n";
            echo "++++++++++++++++++++++++++\n";
            echo count($createdPermissions) . " permission(s) created.\n";
            echo "++++++++++++++++++++++++++\n";
        }

        if (count($deletedPermissions) > 0) {
            $flushFlag = true;
            echo "--------------------------\n";
            echo "DELETED PERMISSIONS\n";
            echo "--------------------------\n";
            echo implode("\n", $deletedPermissions) . "\n";
            echo "--------------------------\n";
            echo count($deletedPermissions) . " permission(s) deleted.\n";
            echo "--------------------------\n";
        }

        if ($flushFlag) {
            Artisan::call('permission:cache-reset');
        } else {
            echo "All permissions are up-to-date.\n";
        }

        return;
    }

    /**
     * Deletes permissions from the database that does not exists in the array.
     *
     * @return array
     */
    private function deletePermissions(): array
    {
        $deleted = [];

        DB::transaction(function () use (&$deleted) {
            Permission::all()
                ->filter(function (Permission $permission) {
                    // Filter database permissions that are not in the array.
                    return !in_array(
                        $permission->getAttribute('name'),
                        Permission::ALL,
                        true
                    );
                })
                ->each(function (Permission $permission) use (&$deleted) {
                    // Push the deleted permission to the deleted array.
                    $deleted[] = $permission->getAttribute('name');

                    // Delete the permission
                    $permission->delete();
                });
        });

        return $deleted;
    }

    /**
     * Creates the permissions newly added in the array.
     *
     * @return array
     */
    private function createPermissions(): array
    {
        $created = [];

        DB::transaction(function () use (&$created) {
            collect(Permission::ALL)
                // Make a collection of permissions that are not in the database
                ->diff(Permission::all()->pluck('name'))
                ->each(function (string $permission) use (&$created) {
                    // Add the permission to the created array
                    $created[] = $permission;

                    // Create the permission
                    Permission::create([
                        'name' => $permission
                    ]);
                });
        });

        return $created;
    }
}
