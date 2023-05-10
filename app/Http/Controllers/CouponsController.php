<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;

class CouponsController extends Controller {

    public function store(Request $request) {
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return back()->withErrors('No coupon found');
        }
        session()->put('coupon', [
            'name' => $request->coupon_code,
            'couponID' => $coupon->couponID,
            'discount' => $coupon->discount(Cart::instance('default')->subtotal()),
        ]);
        return redirect('/cart?show=1')->with('success_message', 'Coupon Successfully applied');
    }

    public function destroy() {
        session()->forget('coupon');
        return redirect('/cart?show=1')->with('success_message', 'Coupon Successfully removed');
    }

}
