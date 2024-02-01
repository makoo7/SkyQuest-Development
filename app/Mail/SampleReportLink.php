<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sampleReportLink extends Mailable
{
    use Queueable, SerializesModels;
    public $report;
    public $sampleId;
    public $user;
    public $slug;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report, $sampleId, $user, $slug)
    {
        $this->report = $report;
        $this->sampleId = $sampleId;
        $this->user = $user;
        $this->slug = $slug;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.front.sample_report_link', ['report' => $this->report, 'sampleId' => $this->sampleId,
        'user' => $this->user, 'slug' => $this->slug])
        ->subject('Sample Report Link');
    }
}
