<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailTemplate;

    /**
     * Create a new message instance.
     *
     * @param $mailTemplate
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
        return $this->subject($this->mailTemplate['subject'])->view('reminderMailTemplate');
    }
}
