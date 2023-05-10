<?php

namespace App\View\Components\Home;

use App\Models\Faq;
use Illuminate\View\Component;

class Faqs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return array
     */
    public $faqs;

    public function __construct()
    {
        $this->faqs = Faq::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.faqs');
    }
}
