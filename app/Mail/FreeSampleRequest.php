<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreeSampleRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $samplerequest;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($samplerequest,$isSendToAdmin=0)
    {
        $this->samplerequest = $samplerequest;
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
            return $this->view('emails.admin.samplerequest')->subject(config('app.name') . " - You've a new free sample request from ".($this->samplerequest->name." (".$this->samplerequest->email.")"));
        } else {
            return $this->view('emails.front.samplerequest')->subject(config('app.name') . ' - Free Sample Request');
        }
    }
}
