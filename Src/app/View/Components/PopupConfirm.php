<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PopupConfirm extends Component
{
    public $title;
    public $body;
    public $close;
    public $accept;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null, $body = null, $close = null, $accept = null)
    {
        if(empty($close)){
            $close = __('label.button.cancel');
        }
        if (empty($accept)) {
            $accept = __('label.button.accept');
        }
        if (empty($title)) {
            $title = __('label.confirming_title');
        }
        if (empty($body)) {
            $body = __('label.sure_to_continue');
        }
        $this->title = $title;
        $this->body = $body;
        $this->close = $close;
        $this->accept = $accept;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.popup-confirm');
    }
}
