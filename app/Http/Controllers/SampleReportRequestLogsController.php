<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportSampleRequest;
use App\Models\SampleReportRequestLogs;
use App\Models\User;
use Carbon\Carbon;

class SampleReportRequestLogsController extends Controller
{
    public function index(Request $request){}
    public function store(Request $request)
    {
        $user = base64_decode($request->input('user'));
        $report = base64_decode($request->input('report'));
        $sampleId = base64_decode($request->input('sampleId'));
        $page = $request->input('page');
        $startTime = Carbon::parse($request->input('startTime'))->format('Y-m-d H:i:s');
        $endTime = Carbon::parse($request->input('endTime'))->format('Y-m-d H:i:s');

        if(($user != "") && ($report != "") &&  
        ($sampleId != "") &&  ($page != "") && 
        ($startTime != "") &&  ($endTime != ""))
        {
            $checkLogs = SampleReportRequestLogs::where(['report_id' => $report, 'srr_id' => $sampleId, 'page_id' => $page])->first();
            if(!$checkLogs){
                $log = new SampleReportRequestLogs;
                $log->report_id = $report;
                $log->srr_id = $sampleId;
                $log->page_id = $page;
                $log->start_time = $startTime;
                $log->end_time = $endTime;
                $log->save();
            return true;
            }
        }
        return true;
    }
    public function update(Request $request){}
    public function delete(Request $request){}
    public function list(Request $request){}
}
