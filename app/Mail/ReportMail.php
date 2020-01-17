<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;
    public $emailData;
    public $project;
    public $date;

    /**
     * Create a new message instance.
     *
     * @param $emailData
     * @param $project
     * @param $date
     */
    public function __construct($emailData, $project, $date)
    {
        $this->emailData = $emailData;
        $this->project = $project;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->emailData['subject'])->view('vendor.emailTemplates.reportTemplate');
    }
}
