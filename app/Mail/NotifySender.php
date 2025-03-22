<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySender extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $document;
    public $signerEmail;
    public $signerName;
    public function __construct($signerName, $signerEmail, $document)
    {
        $this->signerName = $signerName;
        $this->signerEmail = $signerEmail;
        $this->document = $document;    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return
            $this->from(env('MAIL_FROM_ADDRESS'), 'E-sign')
                ->subject("A new sign has been added")
                ->view('notifySender', ['senderName' => $this->signerName, 'senderEmail' => $this->signerEmail, 'document' => $this->document]);
    }
}
