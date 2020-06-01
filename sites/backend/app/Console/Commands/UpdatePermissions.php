<?php

namespace App\Console\Commands;

use App\Models\Permission as Permissions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;

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
        $created = 0;

        foreach (Permissions::ALL as $permission) {
            try {
                // Try to find the permission by name.
                Permission::findByName($permission);
            } catch (PermissionDoesNotExist $exception) {
                // Permission does not exist and should be created
                Permission::create([
                    'name' => $permission
                ]);

                $created++;
            }
        }

        if ($created > 0) {
            Artisan::call('permission:cache-reset');
        }

        echo $created . ' permission(s) created.' . "\n";
        return;
    }
}
