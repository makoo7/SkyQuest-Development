<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\pages;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'id' => 1,
                'slug' => 'home',
                'h1' => 'Home H1',
                'meta_title' => 'Home Meta Title',
                'meta_description' => 'Home Meta Description',
                'page_name' => 'home',
                'page_title' => 'Home',
                'meta_keyword' => 'Home Meta Keyword',
            ],
            [
                'id' => 2,
                'slug' => 'services',
                'h1' => 'Services H1',
                'meta_title' => 'Services Meta Title',
                'meta_description' => 'Services Meta Description',
                'page_name' => 'Services',
                'page_title' => 'Services',
                'meta_keyword' => 'Service Meta Keyword',
            ],
            [
                'id' => 3,
                'slug' => 'insights',
                'h1' => 'Insights H1',
                'meta_title' => 'Insights Meta Title',
                'meta_description' => 'Insights Meta Description',
                'page_name' => 'Insights',
                'page_title' => 'Insights',
                'meta_keyword' => 'Insights Meta Keyword',
            ],
            [
                'id' => 4,
                'slug' => 'case-studies',
                'h1' => 'Case Studies H1',
                'meta_title' => 'Case Studies Meta Title',
                'meta_description' => 'Case Studies Meta Description',
                'page_name' => 'Case Studies',
                'page_title' => 'Case Studies',
                'meta_keyword' => 'Case Studies Meta Keyword',
            ],
            [
                'id' => 5,
                'slug' => 'reports',
                'h1' => 'Reports H1',
                'meta_title' => 'Reports Meta Title',
                'meta_description' => 'Reports Meta Description',
                'page_name' => 'Reports',
                'page_title' => 'Reports',
                'meta_keyword' => 'Reports Meta Keyword',
            ],
            [
                'id' => 6,
                'slug' => 'careers',
                'h1' => 'Careers H1',
                'meta_title' => 'Careers Meta Title',
                'meta_description' => 'Careers Meta Description',
                'page_name' => 'Careers',
                'page_title' => 'Careers',
                'meta_keyword' => 'Careers Meta Keyword',
            ],
            [
                'id' => 7,
                'slug' => '404',
                'h1' => '404 - Page Not Found',
                'meta_title' => '404 Meta Title',
                'meta_description' => '404 Meta Description',
                'page_name' => '404',
                'page_title' => '404 - Page Not Found',
                'meta_keyword' => '404 Meta Keyword',
            ],
            [
                'id' => 8,
                'slug' => 'cookies',
                'h1' => 'Cookies H1',
                'meta_title' => 'Cookies Meta Title',
                'meta_description' => 'Cookies Meta Description',
                'page_name' => 'Cookies',
                'page_title' => 'Cookies',
                'meta_keyword' => 'Cookies Meta Keyword',
            ],
            [
                'id' => 9,
                'slug' => 'privacy',
                'h1' => 'Privacy Policy H1',
                'meta_title' => 'Privacy Policy Meta Title',
                'meta_description' => 'Privacy Policy Meta Description',
                'page_name' => 'Privacy Policy',
                'page_title' => 'Privacy Policy',
                'meta_keyword' => 'Privacy Policy Meta Keyword',
            ],
            [
                'id' => 10,
                'slug' => 'about-us',
                'h1' => 'About Us H1',
                'meta_title' => 'About Us Meta Title',
                'meta_description' => 'About Us Meta Description',
                'page_name' => 'About Us',
                'page_title' => 'About Us',
                'meta_keyword' => 'About Us Meta Keyword',
            ],
            [
                'id' => 11,
                'slug' => 'contact-us',
                'h1' => 'Contact Us H1',
                'meta_title' => 'Contact Us Meta Title',
                'meta_description' => 'Contact Us Meta Description',
                'page_name' => 'Contact Us',
                'page_title' => 'Contact Us',
                'meta_keyword' => 'Contact Us Meta Keyword',
            ],
        ];
        if(count($pages) > 0) {
            foreach($pages as $page)
            {
                if(!(pages::where('id',$page['id'])->exists())) {
                    pages::create($page);
                }
            }
        }
    }
}
