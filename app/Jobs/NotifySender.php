<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifySender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $document;
    public $signerEmail;
    public $signerName;

    /**
     * Create a new job instance.
     */
    public function __construct($signerName, $signerEmail, $document)
    {
        $this->signerName = $signerName;
        $this->signerEmail = $signerEmail;
        $this->document = $document;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->document->sender_email)->send(new \App\Mail\NotifySender($this->signerName, $this->signerEmail, $this->document));
    }
}
