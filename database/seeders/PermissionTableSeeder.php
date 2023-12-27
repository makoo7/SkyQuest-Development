<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        $permissions = [
            ['name' => 'admin-list', 'module_name' => 'Admins'],
            ['name' => 'admin-add', 'module_name' => 'Admins'],
            ['name' => 'admin-edit', 'module_name' => 'Admins'],
            ['name' => 'admin-delete', 'module_name' => 'Admins'],
            ['name' => 'role-list', 'module_name' => 'Roles'],
            ['name' => 'role-add', 'module_name' => 'Roles'],
            ['name' => 'role-edit', 'module_name' => 'Roles'],
            ['name' => 'role-delete', 'module_name' => 'Roles'],
            ['name' => 'report-list', 'module_name' => 'Reports'],
            ['name' => 'report-add', 'module_name' => 'Reports'],
            ['name' => 'report-edit', 'module_name' => 'Reports'],
            ['name' => 'report-delete', 'module_name' => 'Reports'],
            ['name' => 'report-import', 'module_name' => 'Reports'],
            ['name' => 'free-sample-request-list', 'module_name' => 'Free Sample Request'],
            ['name' => 'free-sample-request-view', 'module_name' => 'Free Sample Request'],
            ['name' => 'free-sample-request-delete', 'module_name' => 'Free Sample Request'],
            ['name' => 'free-sample-request-export', 'module_name' => 'Free Sample Request'],
            ['name' => 'report-inquiry-list', 'module_name' => 'Report Inquiry'],
            ['name' => 'report-inquiry-view', 'module_name' => 'Report Inquiry'],
            ['name' => 'report-inquiry-delete', 'module_name' => 'Report Inquiry'],
            ['name' => 'report-inquiry-export', 'module_name' => 'Report Inquiry'],
            ['name' => 'report-subscription-list', 'module_name' => 'Report Subscription'],
            ['name' => 'report-subscription-view', 'module_name' => 'Report Subscription'],
            ['name' => 'report-subscription-delete', 'module_name' => 'Report Subscription'],
            ['name' => 'report-subscription-export', 'module_name' => 'Report Subscription'],
            ['name' => 'report-order-list', 'module_name' => 'Report Order'],
            ['name' => 'report-order-view', 'module_name' => 'Report Order'],
            ['name' => 'report-order-delete', 'module_name' => 'Report Order'],
            ['name' => 'report-order-export', 'module_name' => 'Report Order'],
            ['name' => 'service-list', 'module_name' => 'Services'],
            ['name' => 'service-add', 'module_name' => 'Services'],
            ['name' => 'service-edit', 'module_name' => 'Services'],
            ['name' => 'service-delete', 'module_name' => 'Services'],
            ['name' => 'career-list', 'module_name' => 'Careers'],
            ['name' => 'career-add', 'module_name' => 'Careers'],
            ['name' => 'career-edit', 'module_name' => 'Careers'],
            ['name' => 'career-delete', 'module_name' => 'Careers'],
            ['name' => 'user-list', 'module_name' => 'Users'],
            ['name' => 'user-edit', 'module_name' => 'Users'],
            ['name' => 'user-export', 'module_name' => 'Users'],
            ['name' => 'sectors-list', 'module_name' => 'Sectors'],
            ['name' => 'sectors-add', 'module_name' => 'Sectors'],
            ['name' => 'sectors-edit', 'module_name' => 'Sectors'],
            ['name' => 'sectors-delete', 'module_name' => 'Sectors'],
            ['name' => 'casestudy-list', 'module_name' => 'Case Study'],
            ['name' => 'casestudy-add', 'module_name' => 'Case Study'],
            ['name' => 'casestudy-edit', 'module_name' => 'Case Study'],
            ['name' => 'casestudy-delete', 'module_name' => 'Case Study'],
            ['name' => 'award-list', 'module_name' => 'Awards'],
            ['name' => 'award-add', 'module_name' => 'Awards'],
            ['name' => 'award-edit', 'module_name' => 'Awards'],
            ['name' => 'award-delete', 'module_name' => 'Awards'],
            ['name' => 'insight-list', 'module_name' => 'Insights'],
            ['name' => 'insight-add', 'module_name' => 'Insights'],
            ['name' => 'insight-edit', 'module_name' => 'Insights'],
            ['name' => 'insight-delete', 'module_name' => 'Insights'],
            ['name' => 'client-feedback-list', 'module_name' => 'Client Feedback'],
            ['name' => 'client-feedback-add', 'module_name' => 'Client Feedback'],
            ['name' => 'client-feedback-edit', 'module_name' => 'Client Feedback'],
            ['name' => 'client-feedback-delete', 'module_name' => 'Client Feedback'],
            ['name' => 'sector-list', 'module_name' => 'Sector'],
            ['name' => 'sector-add', 'module_name' => 'Sector'],
            ['name' => 'sector-edit', 'module_name' => 'Sector'],
            ['name' => 'sector-delete', 'module_name' => 'Sector'],
            ['name' => 'industry-group-list', 'module_name' => 'Industry Group'],
            ['name' => 'industry-group-add', 'module_name' => 'Industry Group'],
            ['name' => 'industry-group-edit', 'module_name' => 'Industry Group'],
            ['name' => 'industry-group-delete', 'module_name' => 'Industry Group'],
            ['name' => 'industry-list', 'module_name' => 'Industry'],
            ['name' => 'industry-add', 'module_name' => 'Industry'],
            ['name' => 'industry-edit', 'module_name' => 'Industry'],
            ['name' => 'industry-delete', 'module_name' => 'Industry'],
            ['name' => 'sub-industry-list', 'module_name' => 'Sub Industry'],
            ['name' => 'sub-industry-add', 'module_name' => 'Sub Industry'],
            ['name' => 'sub-industry-edit', 'module_name' => 'Sub Industry'],
            ['name' => 'sub-industry-delete', 'module_name' => 'Sub Industry'],
            ['name' => 'appointment-list', 'module_name' => 'Appointments'],
            ['name' => 'appointment-view', 'module_name' => 'Appointments'],
            ['name' => 'appointment-export', 'module_name' => 'Appointments'],
            ['name' => 'contactus-list', 'module_name' => 'Contact Us'],
            ['name' => 'contactus-view', 'module_name' => 'Contact Us'],
            ['name' => 'contactus-export', 'module_name' => 'Contact Us'],
            ['name' => 'our-team-list', 'module_name' => 'Our Team'],
            ['name' => 'our-team-add', 'module_name' => 'Our Team'],
            ['name' => 'our-team-edit', 'module_name' => 'Our Team'],
            ['name' => 'our-team-delete', 'module_name' => 'Our Team'],
            ['name' => 'job-application-list', 'module_name' => 'Job Applications'],
            ['name' => 'job-application-view', 'module_name' => 'Job Applications'],
            ['name' => 'job-application-delete', 'module_name' => 'Job Applications'],
            ['name' => 'job-application-export', 'module_name' => 'Job Applications'],
            ['name' => '404-inquiry-list', 'module_name' => '404 Inquiry'],
            ['name' => '404-inquiry-view', 'module_name' => '404 Inquiry'],
            ['name' => '404-inquiry-delete', 'module_name' => '404 Inquiry'],
            ['name' => '404-inquiry-export', 'module_name' => '404 Inquiry'],
            ['name' => 'gallery-list', 'module_name' => 'Gallery'],
            ['name' => 'gallery-view', 'module_name' => 'Gallery'],
            ['name' => 'gallery-add', 'module_name' => 'Gallery'],
            ['name' => 'gallery-edit', 'module_name' => 'Gallery'],
            ['name' => 'homepage', 'module_name' => 'Home Page'],
            ['name' => 'pages-list', 'module_name' => 'Pages'],
            ['name' => 'pages-edit', 'module_name' => 'Pages'],
            ['name' => 'pages-delete', 'module_name' => 'Pages'],
            ['name' => 'system-settings', 'module_name' => 'System Settings'],
            ['name' => 'report-pricing', 'module_name' => 'Report Pricing'],
            ['name' => 'report-forecast-settings', 'module_name' => 'Report Forecast Settings'],
            ['name' => 'publish-date', 'module_name'=> 'Publish Date'],
            ['name' => 'email-restriction-list', 'module_name' => 'Email Restriction'],
            ['name' => 'email-restriction-add', 'module_name' => 'Email Restriction'],
            ['name' => 'email-restriction-edit', 'module_name' => 'Email Restriction'],
            ['name' => 'email-restriction-delete', 'module_name' => 'Email Restriction'],
            ['name' => 'report-export', 'module_name' => 'Report Export']
        ];
       
        if(count($permissions) > 0) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('permissions')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            foreach ($permissions as $permission) {
                DB::table('permissions')->insert([
                    ['name' => $permission['name'], 'module_name' => $permission['module_name'], 'guard_name' => 'admin']
                ]);
            }
        }
    }
}
