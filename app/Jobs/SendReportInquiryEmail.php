<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportInquiry;
use Illuminate\Support\Facades\Log;

class SendReportInquiryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $report_inquiry;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report_inquiry)
    {
        $this->report_inquiry = $report_inquiry;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->report_inquiry->email)->send(new ReportInquiry($this->report_inquiry,0));
            Mail::to(getLeadEmails())->send(new ReportInquiry($this->report_inquiry,1));
        } catch (\Exception $e) {
            Log::error('Error while sending report inquiry email.'.$e->getMessage());
        }
    }
}
