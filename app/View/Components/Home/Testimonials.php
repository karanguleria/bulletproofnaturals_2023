<?php

namespace App\View\Components\Home;

use App\Models\Testimonial;
use Illuminate\View\Component;

class Testimonials extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
     
    public $testimonials = '';
    public function __construct()
    {
       
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {  $this->testimonials = Testimonial::all();
        return view('components.home.testimonials');
    }
}
