<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\FreeSampleRequest;
use Illuminate\Support\Facades\Log;

class SendFreeSampleRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $samplerequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($samplerequest)
    {
        $this->samplerequest = $samplerequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->samplerequest->email)->send(new FreeSampleRequest($this->samplerequest,0));
            Mail::to(getLeadEmails())->send(new FreeSampleRequest($this->samplerequest,1));
        } catch (\Exception $e) {
            Log::error('Error while sending free sample request email.'.$e->getMessage());
        }
    }
}
