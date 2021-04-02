<?php

namespace App\Jobs;

use App\Mail\ApplicationNoticeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMailBackGround implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailable;
    protected $mailTpl;
    protected $title;
    protected $to;
    protected $cc;
    protected array $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailable, $to, $cc)
    {
        $this->mailable = $mailable;
        $this->to = $to;
        $this->cc = $cc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->to)->cc($this->cc)->send($this->mailable);
    }
}
