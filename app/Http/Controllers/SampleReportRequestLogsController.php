<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportSampleRequest;
use App\Models\SampleReportRequestLogs;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ReportSegment;
use PhpOffice\PhpPresentation\PhpPresentation;
// use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
// use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\Shape\RichText;
// use PhpOffice\PhpPresentation\PHPPresentation;

class SampleReportRequestLogsController extends Controller
{
    public function index(Request $request){
        $title = "Sample Report Logs";
        return view('admin.sample-report-logs.index', compact('title'));
    }
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
            if(!$checkLogs)
            {
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
    public function ajax(Request $request)
    {
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $email_restrictions = new SampleReportRequestLogs();
        // if ($request->keyword) {
        //     $search = $request->keyword;
        //     $email_restrictions = $email_restrictions->where(function ($q) use ($search) {
        //         $q->Where('email_domain', 'LIKE', "%{$search}%");
        //         $q->orWhere('email_category', 'LIKE', "%{$search}%");
        //     });
        // }
        
        // if ($request->sort_by) {
            // $email_restrictions = $email_restrictions->orderBy($request->sort_by, $request->sort_order);
        // } else {
            $email_restrictions = $email_restrictions->orderBy('id', 'Desc');
        // }
        $email_restrictions_count = $email_restrictions->count();
        $email_restrictions = $email_restrictions->paginate($per_page_record); 
        return view('admin.sample-report-logs.pagination', compact('email_restrictions', 'request', 'email_restrictions_count'));	
    }
    public function generatePresentation(Request $request)
    {
        // Create a new PHPPresentation instance
            $presentation = new PhpPresentation();
            $report = Report::find(63);

            $slide = $presentation->getActiveSlide();

            $shape = $slide->createDrawingShape();

            $shape->setName('Sample image')
            ->setPath(public_path('assets/frontend/slide/1/img/1.jpg'))
            ->setOffsetX(850)
            ->setOffsetY(10);
            $shape1 = $slide->createDrawingShape();

            $shape1->setName('Sample image')
            ->setPath(public_path('assets/frontend/slide/1/img/1.png'))
            ->setHeight(36)
            ->setOffsetX(0)
            ->setOffsetY(0);

            // Add a slide
            $shape = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(20)
            ->setOffsetY(20);

            $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun($report->name);
            $textRun->getFont()->setBold(true);

            $shapetext = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(20)
            ->setOffsetY(45);

            $shapetext->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun2 = $shapetext->createTextRun('MARKET REPORT 2023');
            $textRun2->getFont()->setBold(true);

            $segment1 = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(20)
            ->setOffsetY(65);

            $segment1->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun2 = $segment1->createTextRun('segment 1: ');
            $textRun2->getFont()->setBold(true);
            

            $segment2 = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(20)
            ->setOffsetY(85);

            $segment2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun2 = $segment2->createTextRun('segment 2: ');
            $textRun2->getFont()->setBold(true);

            $segment3 = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(20)
            ->setOffsetY(105);

            $segment3->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun2 = $segment3->createTextRun('segment 3: ');
            $textRun2->getFont()->setBold(true);
                        //  ->setSize(60)
                        //  ->setColor(new Color('FFE06B20'));

            

        // Save the presentation
        $writer = new \PhpOffice\PhpPresentation\Writer\PowerPoint2007($presentation);
        $writer->save('presentation.pptx');

        return 'Presentation generated successfully!';
    }

    public function downloadPPT(Request $request){
        $report = Report::find(63);
        $segments = ReportSegment::where('report_id', $report->id)
                    ->get()->pluck('value', 'name')->toArray();
        return view('front.download-report.index', compact('report', 'segments'));
    }
}
