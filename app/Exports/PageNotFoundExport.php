<?php

namespace App\Exports;

use App\Models\PageNotFoundInquiry;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class PageNotFoundExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            return[
                'Country',
                'Designation',
                'Description',
                'Created Date/Time'
            ];
        }else{
            return[
                'Name',
                'Email',
                'Company Name',
                'Phone',
                'Country',
                'Designation',
                'Description',
                'Created Date/Time'
            ];
        }
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $pagenotfound = PageNotFoundInquiry::with('country')->get();

        $pagenotfound = new PageNotFoundInquiry;
        if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
            $pagenotfound = $pagenotfound->select(
                DB::raw('(SELECT name FROM countries WHERE countries.id = 404_inquiry.country_id) as country_name'),
                '404_inquiry.designation',
                '404_inquiry.description',
                DB::raw("DATE_FORMAT(CONVERT_TZ(404_inquiry.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();
        }else{
            $pagenotfound = $pagenotfound->select(
                '404_inquiry.name',
                '404_inquiry.email',
                '404_inquiry.company_name',
                '404_inquiry.phone',
                DB::raw('(SELECT name FROM countries WHERE countries.id = 404_inquiry.country_id) as country_name'),
                '404_inquiry.designation',
                '404_inquiry.description',
                DB::raw("DATE_FORMAT(CONVERT_TZ(404_inquiry.created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date")
            )->get();
        }
        return $pagenotfound;
    }
}
