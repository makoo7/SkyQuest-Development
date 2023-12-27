<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class AppointmentExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        return[
            'Name',
            'Email',
            'Company Name',
            'Phone',
            'Appointment Date/Time'
        ];
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Appointment::select('name','email','company_name','phone', DB::raw("DATE_FORMAT(CONVERT_TZ(created_at,'+00:00','+05:30'), '%d-%m-%Y %h:%i %p') as created_at_date"))->get();
    }
}
