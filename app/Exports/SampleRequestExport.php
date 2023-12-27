<?php

namespace App\Exports;

use App\Models\ReportSampleRequest;
use App\Models\EmailRestriction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class SampleRequestExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            return[
                'Report Name',
                'Designation',
                'Country',
                'Linkedin Link',
                'Message',
                'Created Date/Time',
                'Legal Category'
            ];
        }else{
            return[
                'Report Name',
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
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();
        $samplerequest = ReportSampleRequest::with('report', 'country');
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            $samplerequest = $samplerequest->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_sample_request.report_id) as report_name'),                
                'report_sample_request.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_sample_request.country_id) as country_name'),            
                'report_sample_request.linkedin_link',
                'report_sample_request.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_sample_request.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date"),
                'report_sample_request.email',
                )->get();
                
                foreach ($samplerequest as $samplerequests) 
                {
                    $emailParts = explode('@', $samplerequests->email);
                    $emailDomain = end($emailParts);
                    // Check if the email domain exists in email restrictions
                    if (array_key_exists($emailDomain, $emailRestrictions)) 
                    {
                        $samplerequests->legal_category = $emailRestrictions[$emailDomain];
                    } 
                    else{
                        $subparts = explode('.', $emailDomain); // Split the domain by .
                        $domain = end($subparts);
                        if (count($subparts) >= 2) {
                            $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                    
                            if (array_key_exists($subdomain, $emailRestrictions)) {
                                $samplerequests->legal_category = $emailRestrictions[$subdomain];
                            } else {
                                $samplerequests->legal_category = 'Corporate';
                            }
                        } else {
                            $samplerequests->legal_category = 'Corporate';
                        }
                    }
                    unset($samplerequests->email); 
                }

        }else{
            $samplerequest = $samplerequest->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_sample_request.report_id) as report_name'),
                'report_sample_request.name',
                'report_sample_request.email',
                DB::raw("CONCAT(ifnull(report_sample_request.phonecode,''),ifnull(report_sample_request.phone,'')) AS phone"),
                'report_sample_request.company_name',
                'report_sample_request.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_sample_request.country_id) as country_name'),            
                'report_sample_request.linkedin_link',
                'report_sample_request.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_sample_request.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();

            foreach ($samplerequest as $samplerequests) {
                $emailParts = explode('@', $samplerequests->email);
                $emailDomain = end($emailParts);
                
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $samplerequests->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                        $domain = end($subparts);
                        if (count($subparts) >= 2) {
                            $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                    
                            if (array_key_exists($subdomain, $emailRestrictions)) {
                                $samplerequests->legal_category = $emailRestrictions[$subdomain];
                            } else {
                                $samplerequests->legal_category = 'Corporate';
                            }
                        } else {
                            $samplerequests->legal_category = 'Corporate';
                        }
                    // $samplerequests->legal_category = 'corporate';
                }
            }
        }
        
        return $samplerequest;
    }
}
