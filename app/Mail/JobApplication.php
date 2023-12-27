<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplication extends Mailable
{
    use Queueable, SerializesModels;
    public $jobapplication;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobapplication,$isSendToAdmin=0)
    {
        $this->jobapplication = $jobapplication;
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
            return $this->view('emails.admin.jobapplication')->subject(config('app.name') . " - You've a new job application request from ".($this->jobapplication->name." (".$this->jobapplication->email.")"));
        } else {
            return $this->view('emails.front.jobapplication')->subject(config('app.name') . ' - Job Application Request');
        }
    }
}
