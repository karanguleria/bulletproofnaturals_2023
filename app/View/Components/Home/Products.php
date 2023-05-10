<?php

namespace App\View\Components\Home;

use App\Models\Product_variants;
use Illuminate\View\Component;

class Products extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $productVariant; 
    public function __construct()
    {
        $this->productVariant = Product_variants::where('product_id',1)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.products');
    }
}