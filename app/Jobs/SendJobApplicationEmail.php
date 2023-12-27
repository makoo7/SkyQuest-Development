<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobApplication;
use Illuminate\Support\Facades\Log;

class SendJobApplicationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $jobapplication;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jobapplication)
    {
        $this->jobapplication = $jobapplication;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->jobapplication->email)->send(new JobApplication($this->jobapplication,0));
            Mail::to(getHREmails())->cc(getCCEmails())->send(new JobApplication($this->jobapplication,1));
        } catch (\Exception $e) {
            Log::error('Error while sending job application email.'.$e->getMessage());
        }
    }
}
