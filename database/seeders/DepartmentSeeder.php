<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'id' => 1,
                'name' => 'Sales',
                'slug' => 'sales',
            ],
            [
                'id' => 2,
                'name' => 'Marketing',
                'slug' => 'marketing',
            ],
            [
                'id' => 3,
                'name' => 'Technology',
                'slug' => 'technology',
            ],
            [
                'id' => 4,
                'name' => 'HR',
                'slug' => 'hr',
            ],
            [
                'id' => 5,
                'name' => 'Social Media',
                'slug' => 'social-media',
            ],
            [
                'id' => 6,
                'name' => 'Project Manager',
                'slug' => 'project-manager',
            ],
        ];
        if(count($departments) > 0) {
            foreach($departments as $department)
            {
                if(!(Department::where('id',$department['id'])->exists())) {
                    Department::create($department);
                }
            }
        }
    }
}
