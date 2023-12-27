<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuyNow extends Mailable
{
    use Queueable, SerializesModels;
    public $report_order;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report_order,$isSendToAdmin=0)
    {
        $this->report_order = $report_order;
        $this->isSendToAdmin = $isSendToAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->isSendToAdmin) {
            return $this->view('emails.admin.buynow')->subject(config('app.name') . " - You've a new payment pending request from ".($this->report_order->name." (".$this->report_order->email.")"));
        } else {
            return $this->view('emails.front.buynow')->subject(config('app.name') . ' - Payment Pending Request');
        }
    }
}
