<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailTpl;
    protected $title;
    protected array $msgParams;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailTpl, $title, array $msgParams)
    {
        $this->mailTpl = $mailTpl;
        $this->title = $title;
        $this->msgParams = $msgParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text($this->mailTpl)
            ->subject($this->title)
            ->with($this->msgParams);
    }
}
