<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $mailTemplate;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailTemplate)
    {
        $this->mailTemplate = $mailTemplate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->mailTemplate['subject'])->view('mailTemplate');
    }
}
