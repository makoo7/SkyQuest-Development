<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportSubscribeNow;
use Illuminate\Support\Facades\Log;

class SendReportSubscribeNowEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subscribenow;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscribenow)
    {
        $this->subscribenow = $subscribenow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->subscribenow->email)->send(new ReportSubscribeNow($this->subscribenow,0));
            Mail::to(getLeadEmails())->send(new ReportSubscribeNow($this->subscribenow,1));
        } catch (\Exception $e) {
            Log::error('Error while sending report subscription email.'.$e->getMessage());
        }
    }
}
