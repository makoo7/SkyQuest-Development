<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\PageNotFoundInquiry;
use Illuminate\Support\Facades\Log;

class SendPageNotFoundInquiryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $pagenotfound;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pagenotfound)
    {
        $this->pagenotfound = $pagenotfound;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->pagenotfound->email)->send(new PageNotFoundInquiry($this->pagenotfound,0));
            Mail::to(getAdminEmails())->cc(getCCEmails())->send(new PageNotFoundInquiry($this->pagenotfound,1));            
        } catch (\Exception $e) {
            Log::error('Error while sending 404 page not found email.'.$e->getMessage());
        }
    }
}
