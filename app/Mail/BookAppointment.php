<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookAppointment extends Mailable
{
    use Queueable, SerializesModels;
    public $appointment;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($appointment,$isSendToAdmin=0)
    {
        $this->appointment = $appointment;
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
            return $this->view('emails.admin.appointment')->subject(config('app.name') . " - You've a new appointment request from ".($this->appointment->name." (".$this->appointment->email.")"));
        } else {
            return $this->view('emails.front.appointment')->subject(config('app.name') . ' - Appointment Request');
        }
    }
}
