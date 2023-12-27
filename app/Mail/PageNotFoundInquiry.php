<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PageNotFoundInquiry extends Mailable
{
    use Queueable, SerializesModels;
    public $pagenotfound;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pagenotfound,$isSendToAdmin=0)
    {
        $this->pagenotfound = $pagenotfound;
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
            return $this->view('emails.admin.pagenotfound')->subject(config('app.name') . " - You've a new page not found request from ".($this->pagenotfound->name." (".$this->pagenotfound->email.")"));
        } else {
            return $this->view('emails.front.pagenotfound')->subject(config('app.name') . ' - Page not found Request');
        }
    }
}
