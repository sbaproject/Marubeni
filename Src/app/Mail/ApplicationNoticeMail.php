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
    protected array $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailTemplate, $title, array $msgParams)
    {
        $this->mailTpl = $mailTemplate;
        $this->title = $title;
        $this->params = $msgParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->mailTpl)
            ->subject($this->title)
            ->with($this->params);
    }
}
