<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $contactus;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contactus,$isSendToAdmin=0)
    {
        $this->contactus = $contactus;
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
            return $this->view('emails.admin.contactus')->subject(config('app.name') . " - You've a new contact us request from ".($this->contactus->name." (".$this->contactus->email.")"));
        } else {
            return $this->view('emails.front.contactus')->subject(config('app.name') . ' - Contact Us Request');
        }
    }
}
