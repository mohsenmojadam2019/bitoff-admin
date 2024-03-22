<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            1 => 'super admin',
            2 => 'Just Monitor',
            3 => 'moderate'
        ];

        foreach ($data as $id => $item) {
            Role::updateOrCreate([
                'id' => $id,
                'name' => $item,
                'guard_name' => 'web'
            ]);
        }
    }
}
