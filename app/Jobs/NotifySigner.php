<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifySigner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $signers;
    public $url;

    /**
     * Create a new job instance.
     */
    public function __construct($signers)
    {
        $this->signers = $signers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(!empty($this->signers)){
            foreach ($this->signers as $email => $shortUrl)
            {
                Mail::to($email)->send(new \App\Mail\NotifySigner($shortUrl));
            }
        }
    }
}
