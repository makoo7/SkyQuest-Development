<?php

namespace App\Exports;

use App\Models\JobApplication;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobApplicationExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        return[
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Work Experience',
            'Notice Period',
            'Current CTC',
            'Expected CTC',
            'Resume',
            'Portfolio/Web URL'
        ];
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return JobApplication::select('first_name','last_name','email','phone', 'work_experience','notice_period','current_ctc','expected_ctc','resume','portfolio_or_web')->get();
    }
}
