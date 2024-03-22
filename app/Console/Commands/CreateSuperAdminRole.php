<?php

namespace App\Console\Commands;

use App\Support\ACL;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSuperAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create super admin role and grant all permissions';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = Role::findOrCreate(ACL::SUPER_ADMIN_ROLE);

        Artisan::call('permission:db');

        Permission::all()->each(function ($permission) use ($role) {
            $role->givePermissionTo($permission);
        });

    }
}
