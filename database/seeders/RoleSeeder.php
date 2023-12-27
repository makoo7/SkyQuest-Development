<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'guard_name' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'guard_name' => 'admin',
            ],
        ];
        if(count($roles) > 0) {
            foreach($roles as $role)
            {
                if(!(Role::where('id',$role['id'])->exists())) {
                    Role::create($role);
                }
            }
        }
    }
}
