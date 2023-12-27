<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUs;
use Illuminate\Support\Facades\Log;

class SendContactUsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $contactus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contactus)
    {
        $this->contactus = $contactus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->contactus->email)->send(new ContactUs($this->contactus,0));            
            Mail::to(getAdminEmails())->cc(getCCEmails())->send(new ContactUs($this->contactus,1));
        } catch (\Exception $e) {
            Log::error('Error while sending contact us email.'.$e->getMessage());
        }
    }
}
