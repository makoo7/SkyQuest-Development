<?php

namespace App\Exports;

use App\Models\ReportInquiry;
use App\Models\EmailRestriction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ReportInquiryExport implements FromCollection,WithHeadings
{
    public function __construct($filter){
        $this->filter = $filter;
    }
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
                'Legal Category'
            ];
        }
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $search = $this->filter['search'];
        $start_date = $this->filter['start_date'];
        $end_date = $this->filter['end_date'];
        $report_inquiries = ReportInquiry::withAggregate('report','name');
        $report_inquiries->join('reports', 'reports.id', '=', 'report_inquiry.report_id');
        $report_inquiries->select('report_inquiry.*','reports.name as report_name');
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            //     $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
            //         $q->Where('reports.name', 'LIKE', "%{$search}%");
            //         $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
            //         $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
            //     });
            // else{
            //     $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
            //         $q->Where('report_inquiry.name', 'LIKE', "%{$search}%");
            //         $q->orWhere('email', 'LIKE', "%{$search}%");
            //         $q->orWhere('phone', 'LIKE', "%{$search}%");
            //         $q->orWhere('reports.name', 'LIKE', "%{$search}%");
            //         $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
            //         $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
            //     });
            // }
            if($search){
                $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
                    $q->Where('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
            // if($this->filter['search'] && $this->filter['start_date'] && $this->filter['end_date']){
            //     $report_inquiries = $report_inquiries->whereDate('created_at','>=',$this->filter['start_date'])->whereDate('created_at','<=',$this->filter['end_date'])->report->where('name',$this->filter['search']);
            // }else if($this->filter['search'] && $this->filter['start_date']){
            //     $report_inquiries = $report_inquiries->whereDate('created_at','>=',$this->filter['start_date'])->report->where('name',$this->filter['search']);
            // }else if($this->filter['search'] && $this->filter['end_date']){
            //     $report_inquiries = $report_inquiries->whereDate('created_at','<=',$this->filter['end_date'])->report->where('name',$this->filter['search']);
            // }else if($this->filter['search']){
            //     $report_inquiries = $report_inquiries->where('reports.name',$this->filter['search']);
            // }else if($this->filter['start_date']) {
            //     $report_inquiries = $report_inquiries->whereDate('created_at','>=',$this->filter['start_date']);
            //     dd($report_inquiries);
            //     }else if($this->filter['end_date']) {
            //         $report_inquiries = $report_inquiries->whereDate('created_at','<=',$this->filter['end_date']);
            //     }
            if($start_date && $end_date){
                $report_inquiries = $report_inquiries->where(function($q) use ($start_date,$end_date){
                    $q->whereDate('report_inquiry.created_at','>=',$start_date)
                    ->whereDate('report_inquiry.created_at','<=',$end_date);
                });
            }
            if($start_date) {
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','>=',$start_date);
            }
            if($end_date) {
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','<=',$end_date);
            }
            $report_inquiries = $report_inquiries->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_inquiry.report_id) as report_name'),                
                'report_inquiry.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_inquiry.country_id) as country_name'),            
                'report_inquiry.linkedin_link',
                'report_inquiry.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_inquiry.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date"),
                'report_inquiry.email',
            )->get();

            foreach ($report_inquiries as $report_inquiry) 
            {
                $emailParts = explode('@', $report_inquiry->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_inquiry->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                        $domain = end($subparts);
                        if (count($subparts) >= 2) {
                            $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                    
                            if (array_key_exists($subdomain, $emailRestrictions)) {
                                $report_inquiry->legal_category = $emailRestrictions[$subdomain];
                            } else {
                                $report_inquiry->legal_category = 'Corporate';
                            }
                        } else {
                            $report_inquiry->legal_category = 'Corporate';
                        }
                }
                unset($report_inquiry->email); 
            }

        }
        else{
            // if($this->filter['search'] && $this->filter['start_date'] && $this->filter['end_date']){
            //     $report_inquiries = $report_inquiries->whereDate('created_at','>=',$this->filter['start_date'])->whereDate('created_at','<=',$this->filter['end_date'])->report->where('name',$this->filter['search']);
            // }
            // if($this->filter['search'] && $this->filter['start_date']){
            //     $report_inquiries = $report_inquiries->whereDate('created_at','>=',$this->filter['start_date'])->report->where('name',$this->filter['search']);
            // }
            // if($this->filter['search'] && $this->filter['end_date']){
            //     $report_inquiries = $report_inquiries->where('created_at','<=',$this->filter['end_date'])->report->where('name',$this->filter['search']);
            // }
            if($search){
                $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
                    $q->Where('report_inquiry.name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                    $q->orWhere('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
            if($start_date && $end_date){
                $report_inquiries = $report_inquiries->where(function($q) use ($start_date,$end_date){
                    $q->whereDate('report_inquiry.created_at','>=',$start_date)
                    ->whereDate('report_inquiry.created_at','<=',$end_date);
                });
            }
            if($start_date) {
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','>=',$start_date);
            }
            if($end_date) {
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','<=',$end_date);
            }
            $report_inquiries = $report_inquiries->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_inquiry.report_id) as report_name'),
                'report_inquiry.name',
                'report_inquiry.email',
                DB::raw("CONCAT(ifnull(report_inquiry.phonecode,''),ifnull(report_inquiry.phone,'')) AS phone"),
                'report_inquiry.company_name',
                'report_inquiry.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_inquiry.country_id) as country_name'),            
                'report_inquiry.linkedin_link',
                'report_inquiry.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_inquiry.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();

            foreach ($report_inquiries as $report_inquiry) 
            {
                $emailParts = explode('@', $report_inquiry->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_inquiry->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $report_inquiry->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $report_inquiry->legal_category = 'Corporate';
                        }
                    } else {
                        $report_inquiry->legal_category = 'Corporate';
                    }
                }
            }
        }
        return $report_inquiries;
    }
}
