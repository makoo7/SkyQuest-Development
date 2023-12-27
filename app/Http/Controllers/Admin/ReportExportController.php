<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\SubIndustry;
use App\Models\Sector;
use App\Models\ReportSegment;
use App\Models\ReportPricing;
use App\Models\ReportFaq;
use App\Models\ReportMetrics;
use App\Models\ReportTableofcontent;
use App\Models\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ReportExportJob;
use App\Exports\ReportExport as ReportExports;
use App\Mail\ReportExport as ReportExportEmail;
use Auth, Mail;
use Illuminate\Support\Str;

class ReportExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:report-export,admin', ['only' => ['index']]);
    }

    public function index()
	{
		$title = 'Report Export';
        $fields = [['name' => 'Title', 'value' => 'title'],
                    ['name' => 'URL', 'value' => 'url'],
                    ['name' => 'Product Code', 'value' => 'product_code'],
                    ['name' => 'Date', 'value' => 'date'],
                    ['name' => 'Length', 'value' => 'length'],
                    ['name' => 'Price: Single User', 'value' => 'single_price'],
                    ['name' => 'Price: Site License', 'value' => 'site_price'],
                    ['name' => 'Price: Enterprise License', 'value' => 'enterprise_price'],
                    ['name' => 'Table of Content', 'value' => 'toc'],
                    ['name' => 'Categories', 'value' => 'categories'],
                    ['name' => 'Countries Covered', 'value' => 'countries_covered'],
                    ['name' => 'Companies Mentioned', 'value' => 'companies_mentioned'],
                    ['name' => 'Products Mentioned', 'value' => 'products_mentioned'],
                    ['name' => '2021', 'value' => '2021'],
                    ['name' => '2022', 'value' => '2022'],
                    ['name' => '2030', 'value' => '2030'],
                    ['name' => 'CAGR %', 'value' => 'cagr'],
                    ['name' => 'Currency', 'value' => 'currency'],
                    ['name' => 'Report Type', 'value' => 'report_type'],
                    ['name' => 'Sector', 'value' => 'sector'],
                    ['name' => 'Region', 'value' => 'region'],
                    ['name' => '1st 2 lines', 'value' => '1st_2_lines'],
                ];
        $selected = ['title', 'url', 'product_code', 'date', 'length', 'single_price', 'site_price', 'enterprise_price', 'toc', 'categories', 'countries_covered', 'companies_mentioned', 'products_mentioned', '2021', '2022', '2030', 'cagr', 'currency'];
		return view('admin.report-export.index', compact('title','fields', 'selected'));
	}

    public function store(Request $request)
    {
        $request->validate([
            'fields' => 'required|array|min:1',
            'start_date' => 'nullable',
            'end_date' => 'nullable|required_with:start_date|after_or_equal:start_date'
        ]);
        $data = $request->except(['_token','_method']);
        
        $user = auth('admin')->user();
        $uuid = (string) Str::uuid();

        $data = ['admin_id' => $user->id, 'uuid' => $uuid, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'fields' => implode(",", $request->fields)];
        $reportExport = ReportExport::create($data);
        
        // send mail to admin for download link
        // Mail::to(getExportReportEmail())->send(new ReportExportEmail($reportExport));  

        $notification = ['message' => 'Report export request sent successfully!','alert-class' => 'success'];
		return redirect()->route('admin.report-export.index')->with($notification);
    }

    public function download($uuid)
    {
        $user = auth('admin')->user();
        $id = $user->id;
        if($id==1){
            $reportexport = ReportExport::where('uuid', $uuid)->first();
        }else{
            $reportexport = ReportExport::where('uuid', $uuid)->where('admin_id', $id)->first();
        }
        //dd($reportexport);
        if($reportexport){
            //dispatch(new ReportExportJob($reportexport));
            return Excel::download(new ReportExports($reportexport), 'reportexport.xlsx');
            $notification = ['message' => 'Report exported successfully!','alert-class' => 'success'];
        }else{
            $notification = ['message' => 'You are not allowed!','alert-class' => 'error'];
        }

        
		return redirect()->route('admin.report-export.index')->with($notification);
    }
}