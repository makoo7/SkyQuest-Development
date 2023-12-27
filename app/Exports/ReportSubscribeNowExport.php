<?php

namespace App\Exports;

use App\Models\ReportSubscribeNow;
use App\Models\EmailRestriction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ReportSubscribeNowExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            return[
                'Report Name',
                'Plan',
                'Designation',
                'Country',
                'Linkedin Link',
                'Message',
                'Created Date/Time',
                'Legal Category',
            ];
        }else{
            return[
                'Report Name',
                'Plan',
                'User Name',
                'Email',
                'Phone',
                'Company Name',
                'Designation',
                'Country',
                'Linkedin Link',
                'Message',
                'Created Date/Time',
                'Legal Category',
            ];
        }
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $report_subscriptions = ReportSubscribeNow::with('country')->get();
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();

        $report_subscriptions = new ReportSubscribeNow;

        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            $report_subscriptions = $report_subscriptions->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_subscribe_now.report_id) as report_name'),
                'report_subscribe_now.plan',
                'report_subscribe_now.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_subscribe_now.country_id) as country_name'),            
                'report_subscribe_now.linkedin_link',
                'report_subscribe_now.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_subscribe_now.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date"),
                'report_subscribe_now.email',
            )->get();

            foreach ($report_subscriptions as $report_subscription) 
            {
                $emailParts = explode('@', $report_subscription->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_subscription->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $report_subscription->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $report_subscription->legal_category = 'Corporate';
                        }
                    } else {
                        $report_subscription->legal_category = 'Corporate';
                    }
                }
                unset($report_subscription->email); 
            }

        }else{
            $report_subscriptions = $report_subscriptions->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_subscribe_now.report_id) as report_name'),
                'report_subscribe_now.plan',
                'report_subscribe_now.name',
                'report_subscribe_now.email',
                DB::raw("CONCAT(ifnull(report_subscribe_now.phonecode,''),ifnull(report_subscribe_now.phone,'')) AS phone"),
                'report_subscribe_now.company_name',
                'report_subscribe_now.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_subscribe_now.country_id) as country_name'),            
                'report_subscribe_now.linkedin_link',
                'report_subscribe_now.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_subscribe_now.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();

            foreach ($report_subscriptions as $report_subscription) 
            {
                $emailParts = explode('@', $report_subscription->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_subscription->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $report_subscription->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $report_subscription->legal_category = 'Corporate';
                        }
                    } else {
                        $report_subscription->legal_category = 'Corporate';
                    }
                }
            }
        }
        return $report_subscriptions;
    }
}
