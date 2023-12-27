<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportInquiry extends Mailable
{
    use Queueable, SerializesModels;
    public $report_inquiry;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report_inquiry,$isSendToAdmin=0)
    {
        $this->report_inquiry = $report_inquiry;
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
            return $this->view('emails.admin.reportinquiry')->subject(config('app.name') . " - You've a new report inquiry from ".($this->report_inquiry->name." (".$this->report_inquiry->email.")"));
        } else {
            return $this->view('emails.front.reportinquiry')->subject(config('app.name') . ' - Report Inquiry Request');
        }
    }
}
