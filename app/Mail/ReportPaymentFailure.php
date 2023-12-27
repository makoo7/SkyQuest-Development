<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportPaymentFailure extends Mailable
{
    use Queueable, SerializesModels;
    public $paymentfailure;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentfailure,$isSendToAdmin=0)
    {
        $this->paymentfailure = $paymentfailure;
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
            return $this->view('emails.admin.paymentfailure')->subject(config('app.name') . " - Payment failure of ".($this->paymentfailure->name." (".$this->paymentfailure->email.")"));
        } else {
            return $this->view('emails.front.paymentfailure')->subject(config('app.name') . ' - Payment failure for your order - '.$this->paymentfailure->report->name);
        }
    }
}
