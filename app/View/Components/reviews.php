<?php

namespace App\View\Components;

use App\Models\Faq;
use App\Models\Review;
use Illuminate\View\Component;

class reviews extends Component
{
    /**
     * Create a new component instance.
     *
     * @return array
     */
    public $reviews, $faqs;
    public $product, $review_count, $avg_rating, $avg_rating_1, $avg_rating_2, $avg_rating_3, $avg_rating_4, $avg_rating_5;
    public function __construct()
    {
        $this->product = (object)[
            'description' => "Dummy Text",
            'id' => 1
        ];
        $this->reviews = Review::where('product_id', $this->product->id)->where('status', 'Approved')->orderBy('id', 'desc')->get();
        $this->review_count = Review::where('product_id', $this->product->id)->where('status', 'Approved')->orderBy('id', 'desc')->count();
        $this->avg_rating = Review::where('product_id', $this->product->id)->where('status', 'Approved')->avg('rating');
        $this->avg_rating_1 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 1)->count();
        $this->avg_rating_2 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 2)->count();
        $this->avg_rating_3 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 3)->count();
        $this->avg_rating_4 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 4)->count();
        $this->avg_rating_5 = Review::where('product_id', $this->product->id)->where('status', 'Approved')->where('rating', 5)->count();

        $this->faqs = Faq::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.reviews');
    }
}
