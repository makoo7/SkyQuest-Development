<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserCreate;
use Illuminate\Support\Facades\Log;

class SendNewUserCreateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $newusercreate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newusercreate)
    {
        $this->newusercreate = (object) $newusercreate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->newusercreate->email)->send(new NewUserCreate($this->newusercreate,0));
        } catch (\Exception $e) {
            Log::error('Error while sending new user create email.'.$e->getMessage());
        }
    }
}
