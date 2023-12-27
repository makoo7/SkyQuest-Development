<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportPaymentFailure;
use Illuminate\Support\Facades\Log;

class SendReportPaymentFailureEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $paymentfailure;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paymentfailure)
    {
        $this->paymentfailure = $paymentfailure;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->paymentfailure->email)->send(new ReportPaymentFailure($this->paymentfailure,0));
            Mail::to(getAdminEmails())->cc(getCCEmails())->send(new ReportPaymentFailure($this->paymentfailure,1));
        } catch (\Exception $e) {
            Log::error('Error while sending report payment failure email.'.$e->getMessage());
        }
    }
}
