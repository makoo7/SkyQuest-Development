<?php

namespace App\Imports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class UpcomingReportImport implements ToModel, ToCollection, WithEvents
{
    public $sheetNames;
    public $sheetData;
    
    public function __construct(){
        $this->sheetNames = [];
        $this->sheetData = [];
    }

    public function collection(Collection $collection)
    {
        $this->sheetData[] = $collection;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getDelegate()->getTitle();
            }
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Report([
            //
        ]);
    }
}
