<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserCreate extends Mailable
{
    use Queueable, SerializesModels;
    public $newusercreate;
    public $isSendToAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newusercreate,$isSendToAdmin=0)
    {
        $this->newusercreate = $newusercreate;
        $this->isSendToAdmin = $isSendToAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.front.newusercreate')->subject(config('app.name') . ' - User Registration');        
    }
}
