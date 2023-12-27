<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuyNow;
use Illuminate\Support\Facades\Log;

class SendBuyNowEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $report_order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report_order)
    {
        $this->report_order = $report_order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->report_order->email)->cc(getBuyNowUserCCEmails())->replyTo(getBuyNowReplyToEmails())->send(new BuyNow($this->report_order,0));
            Mail::to(getBuyNowEmails())->cc(getBuyNowCCEmails())->send(new BuyNow($this->report_order,1));
        } catch (\Exception $e) {
            Log::error('Error while sending buy now email.'.$e->getMessage());
        }
    }
}
