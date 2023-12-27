<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportPaymentSuccess;
use Illuminate\Support\Facades\Log;

class SendReportPaymentSuccessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $paymentsuccess;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paymentsuccess)
    {
        $this->paymentsuccess = $paymentsuccess;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->paymentsuccess->email)->send(new ReportPaymentSuccess($this->paymentsuccess,0));
            Mail::to(getAccountEmails())->cc(getCCEmails())->send(new ReportPaymentSuccess($this->paymentsuccess,1));
        } catch (\Exception $e) {
            Log::error('Error while sending report payment success email.'.$e->getMessage());
        }
    }
}
