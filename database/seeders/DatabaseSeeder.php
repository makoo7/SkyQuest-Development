<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CountrySeeder::class);
        //$this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(DepartmentSeeder::class);   
        $this->call(CountryPhonecodeSeeder::class);
        //$this->call(HomepageSeeder::class);
        $this->call(PageSeeder::class);
    }
}
