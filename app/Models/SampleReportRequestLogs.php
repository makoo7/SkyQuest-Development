<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SampleReportRequestLogs extends Model
{
    protected $table = "sample_report_request_logs";

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'srr_id',
        'report_id',
        'page_id',
        'start_time',
        'end_time'
    ];
}