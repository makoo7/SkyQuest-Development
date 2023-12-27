<?php

namespace App\Exports;

use App\Models\ReportOrders;
use App\Models\EmailRestriction;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ReportOrdersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            return[
                'Report Name',
                'Report Type',
                'License Type',
                'File Type',
                'Payment Method',
                'Payment Status',
                'Report Price',
                'Designation',
                'Country',
                'Message',
                'Created Date/Time',
                'Legal Category'
            ];
        }else{
            return[
                'Report Name',
                'Report Type',
                'License Type',
                'File Type',
                'Payment Method',
                'Payment Status',
                'Report Price',
                'User Name',
                'Email',
                'Phone',
                'Company Name',
                'Designation',
                'Country',
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
        $report_orders = ReportOrders::with('country')->get();
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();

        $report_orders = new ReportOrders;
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            $report_orders = $report_orders->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_orders.report_id) as report_name'),
                'report_orders.report_type',
                'report_orders.license_type',
                'report_orders.file_type',
                'report_orders.payment_method',
                'report_orders.payment_status',
                'report_orders.price',
                'report_orders.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_orders.country_id) as country_name'),            
                'report_orders.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_orders.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date"),
                'report_orders.email',
            )->get();

            foreach ($report_orders as $report_order) 
            {
                $emailParts = explode('@', $report_order->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_order->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $report_order->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $report_order->legal_category = 'Corporate';
                        }
                    } else {
                        $report_order->legal_category = 'Corporate';
                    }
                }
                unset($report_order->email); 
            }

        }else{
            $report_orders = $report_orders->select(
                DB::raw('(SELECT name FROM reports WHERE reports.id = report_orders.report_id) as report_name'),
                'report_orders.report_type',
                'report_orders.license_type',
                'report_orders.file_type',
                'report_orders.payment_method',
                'report_orders.payment_status',
                'report_orders.price',
                'report_orders.name',
                'report_orders.email',
                DB::raw("CONCAT(ifnull(report_orders.phonecode,''),ifnull(report_orders.phone,'')) AS phone"),
                'report_orders.company_name',
                'report_orders.designation',
                DB::raw('(SELECT name FROM countries WHERE countries.id = report_orders.country_id) as country_name'),            
                'report_orders.message',
                DB::raw("DATE_FORMAT(CONVERT_TZ(report_orders.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();
            foreach ($report_orders as $report_order) 
            {
                $emailParts = explode('@', $report_order->email);
                $emailDomain = end($emailParts);
                // Check if the email domain exists in email restrictions
                if (array_key_exists($emailDomain, $emailRestrictions)) {
                    $report_order->legal_category = $emailRestrictions[$emailDomain];
                } else {
                    $subparts = explode('.', $emailDomain); // Split the domain by .
                    $domain = end($subparts);
                    if (count($subparts) >= 2) {
                        $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                
                        if (array_key_exists($subdomain, $emailRestrictions)) {
                            $report_order->legal_category = $emailRestrictions[$subdomain];
                        } else {
                            $report_order->legal_category = 'Corporate';
                        }
                    } else {
                        $report_order->legal_category = 'Corporate';
                    }
                }
            }
        }
        return $report_orders;
    }
}
