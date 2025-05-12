<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.label');
    }
}
