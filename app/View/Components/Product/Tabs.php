<?php

namespace App\View\Components\Product;

use App\Http\Controllers\ProductController;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Product;
use Illuminate\View\Component;

class Tabs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
     public $avg_rat, $reviews, $faqs, $product, $review_count, $avg_rating, $avg_rating_1, $avg_rating_2, $avg_rating_3, $avg_rating_4, $avg_rating_5;
    
    public function __construct()
    {
         $faqs = Faq::all();
         $this->faq = $faqs ;
         foreach($faqs as $key => $val){
            $this->faqs[$key]['question'] = $val->title ;
            $this->faqs[$key]['answer'] = $val->description ;
            $this->faqs[$key]['isOpen'] = @($key == 0) ? true: false ;
         }
         $this->product = Product::where('id',1)->first();
         
        $this->reviews = Review::where('product_id', $this->product->id)->where('status', 'Approved')->orderBy('id', 'desc')->get();
        $this->review_count = Review::where('product_id', $this->product->id)->where('status', 'Approved')->orderBy('id', 'desc')->count();
        $this->avg_rating = Review::where('product_id', $this->product->id)->where('status', 'Approved')->avg('rating');
        $this->avg_rating_1 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 1)->count();
        $this->avg_rating_2 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 2)->count();
        $this->avg_rating_3 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 3)->count();
        $this->avg_rating_4 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 4)->count();
        $this->avg_rating_5 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 5)->count();
        $this->avg_rat = round($this->avg_rating,2) ;
        // die($faqs);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product.tabs');
    }
}
