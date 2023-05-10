<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Product;
use App\Models\Product_variants;
use App\Models\Review;
use App\Models\Media;
use Illuminate\Http\Request;

class ProductController extends Controller {

    public function show(Product $product) {
        $productVarients = Product_variants::where('product_id', $product->id)->get();
        $images = Media::where('product_id', $product->id)->get();
        $faqs = Faq::All();
        return view('product', compact('product', 'productVarients', 'images', 'faqs'));
    }

    public function review(Request $request, $product_id) {
        Review::create([
            'product_id' => $product_id,
            'description' => request('comment'),
            'rating' => request('rating'),
            'title' => request('title'),
            'fname' => request('fname'),
            'user_id' => '',
        ]);
        return redirect()->back()->with('success_message', "Review Submitted");
    }

}
