<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookAppointment;
use Illuminate\Support\Facades\Log;

class SendBookAppointmentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $appointment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->appointment->email)->send(new BookAppointment($this->appointment,0));
            Mail::to(getAdminEmails())->cc(getCCEmails())->send(new BookAppointment($this->appointment,1));
        } catch (\Exception $e) {
            Log::error('Error while sending book an appointment email.'.$e->getMessage());
        }
    }
}
