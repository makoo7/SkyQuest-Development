<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class SalesReportRequest extends Model
{
    protected $table = "sales_report_requests";

    protected $fillable = [
        'report_id', 'from_id', 'to_id', 'message', 'start_date', 'end_date'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }
}