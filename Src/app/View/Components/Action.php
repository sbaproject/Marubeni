<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Action extends Component
{
    /**
     * This component to generate list actions like this [Edit] | [Delete]
     */

    /**
     * Edit
     */
    public $editUrl;
    public $editTitle;
    public $editDisabled;
    /**
     * Delete
     */
    public $deleteUrl;
    public $deleteTitle;
    public $deleteDisabled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($editUrl = null, $editTitle = null, $editDisabled = null, $deleteUrl = null, $deleteTitle = null, $deleteDisabled= null)
    {
        $this->editUrl = $editUrl;
        $this->editTitle = $editTitle;
        $this->editDisabled = $editDisabled;
        $this->deleteUrl = $deleteUrl;
        $this->deleteTitle = $deleteTitle;
        $this->deleteDisabled = $deleteDisabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.action');
    }
}