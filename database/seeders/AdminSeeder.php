<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'user_name' => 'Super Admin',
            'role_id' => '1',
            'email' => 'skyquest@mailinator.com',
            'password' => \Hash::make('12345678'),
        ];
        if(!(Admin::where('email',$admin['email'])->exists())) {
            $adminUser = Admin::create($admin);
        }else{
            $adminUser = Admin::where('email',$admin['email'])->first();
        }

        $role = Role::updateOrCreate(['name' => 'Super Admin'], ['guard_name' => 'admin']);       
        $permissions = Permission::pluck('id','id')->all();     
        $role->syncPermissions($permissions);       
        $adminUser->assignRole([$role->id]);
    }
}
