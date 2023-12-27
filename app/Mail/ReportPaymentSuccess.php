<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportPaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;
    public $paymentsuccess;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentsuccess,$isSendToAdmin=0)
    {
        $this->paymentsuccess = $paymentsuccess;
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
            return $this->view('emails.admin.paymentsuccess')->subject(config('app.name') . " - Got a new order from ".($this->paymentsuccess->name." (".$this->paymentsuccess->email.")"));
        } else {
            return $this->view('emails.front.paymentsuccess')->subject(config('app.name') . ' - Thank you for your order - '.$this->paymentsuccess->report->name);
        }
    }
}
