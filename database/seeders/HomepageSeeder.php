<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Homepage;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homepage_settings = [
            [
                'id' => 1,
                'is_case_study' => 1,
                'is_feedback' => 1,
                'is_help' => 1,
                'is_insights' => 1,
                'is_process' => 1,
                'is_products' => 1,
                'is_awards' => 1,
            ],
        ];
        if(count($homepage_settings) > 0) {
            foreach($homepage_settings as $homepage)
            {
                if(!(Homepage::where('id',$homepage['id'])->exists())) {
                    Homepage::create($homepage);
                }
            }
        }
    }
}
