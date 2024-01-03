<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\ReportSampleRequest;

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

    public function client()
    {
        return $this->belongsTo(ReportSampleRequest::class, 'srr_id', 'id');
    }
}