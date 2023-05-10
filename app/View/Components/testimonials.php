<?php

namespace App\View\Components;

use App\Models\Testimonial;
use Illuminate\View\Component;

class testimonials extends Component
{
    /**
     * Create a new component instance.
     *
     * @return array
     */
    
    public $testimonials;

    public function __construct()
    {
         $this->testimonials = Testimonial::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
       
        return view('components.testimonials');
    }
}
