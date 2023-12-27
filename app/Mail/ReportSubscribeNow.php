<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportSubscribeNow extends Mailable
{
    use Queueable, SerializesModels;
    public $subscribenow;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscribenow,$isSendToAdmin=0)
    {
        $this->subscribenow = $subscribenow;
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
            return $this->view('emails.admin.subscribenow')->subject(config('app.name') . " - You've a new subscribe request from ".($this->subscribenow->name." (".$this->subscribenow->email.")"));
        } else {
            return $this->view('emails.front.subscribenow')->subject(config('app.name') . ' - Subscribe Request');
        }
    }
}
