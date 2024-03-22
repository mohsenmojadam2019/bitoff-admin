<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SetRoleForAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::findById(1);

        foreach (User::where('admin', true)->get() as $admin) {
            $admin->assignRole($superAdminRole);
        }
    }
}
