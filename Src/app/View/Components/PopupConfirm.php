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
    public function __construct($title, $body, $close = null, $accept = null)
    {
        if(empty($close)){
            $close = __('label.button.cancel');
        }
        if (empty($accept)) {
            $accept = __('label.button.accept');
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
