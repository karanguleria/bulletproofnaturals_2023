<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;
use Illuminate\Support\Str;
class BuyerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $customer_id = Str::random(40);
        session()->put('customer_id', $customer_id);
       if (Cart::instance('default')->count()) {
            $itemdata = [];
            $total_items_regular = 0;
            $total_items_mega = 0;
            foreach(Cart::instance('default')->content() as $item_data){
                if ($item_data->options->varient_type == "mega") {
                    $total_items_mega = $total_items_mega + $item_data->options->varient_months * $item_data->qty;
                } else {
                    $total_items_regular = $total_items_regular + $item_data->options->varient_months * $item_data->qty;
                }
                $itemdata[] = ['variant_id'=> $item_data->options->variant_id,'quantity' => $item_data->qty];
            
            }
            $items = collect(Cart::instance('default')->content())->mapWithKeys(function($item){
                return [$item->options->varient_price_id=> $item->qty] ;
            });
            $billing_tax = getNumbers()->get('newTax');
            $billing_base_total = getNumbers()->get('subtotal');
            $billing_subtotal = getNumbers()->get('subtotal');
            
            if (!empty(\session()->get('coupon'))) {
                $billing_discount = \session()->get('coupon')['discount'];
                $billing_discount_code = \session()->get('coupon')['name'];
                $couponID = trim(session()->get('coupon')['couponID']);
                return \Laravel\Cashier\Checkout::guest()
                    //->withCoupon('FN9FL0ht')
                    ->create(
                        $items->toArray(),
                        array_merge(config('checkout'), [
                            'payment_intent_data' => [
                                'metadata' =>
                                [
                                    'extras' => serialize($itemdata),
                                    'billing_base_total' => $billing_base_total,
                                    'billing_subtotal' => $billing_subtotal,
                                    'billing_discount' => $billing_discount,
                                    'billing_discount_code' => $billing_discount_code

                                ]

                            ],
                        'discounts' => [[
                            'coupon' => $couponID,
                        ]],
                            'success_url' => route('checkout.thankyou'),
                            'cancel_url' => route('checkout.cancel'),
                        ])
                    );

            } else {
                $billing_discount = '0.00';
                $billing_discount_code = 'None';
                return \Laravel\Cashier\Checkout::guest()
                    //->withCoupon('FN9FL0ht')
                    ->create(
                        $items->toArray(),
                        array_merge(config('checkout'), [
                            'payment_intent_data' => [
                                'metadata' =>
                                [
                                    'extras' => serialize($itemdata),
                                    'billing_base_total' => $billing_base_total,
                                    'billing_subtotal' => $billing_subtotal,
                                    'billing_discount' => $billing_discount,
                                    'billing_discount_code' => $billing_discount_code,

                                ]

                            ],
                            'success_url' => route('checkout.thankyou'),
                            'cancel_url' => route('checkout.cancel'),
                        ])
                    );
            }
                
            }
            
            

    }
    public function saveBuyer(Request $request)
    {


        if (Cart::instance('default')->count()) {
            $request->validate([
                'email' => 'required|email:rfc,dns|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'fname' => 'required|regex:/^[a-zA-Z ]+$/u',
                'lname' => 'required|regex:/^[a-zA-Z ]+$/u',
                'street' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zip' => 'required',
                'phone' => 'required',
            ]);

            $buyer = Buyer::updateOrCreate(
                $request->only('email'),
                $request->only('fname', 'fname', 'lname', 'email', 'street', 'apartement', 'city', 'state', 'zip', 'phone')
            );
            if ($buyer->id != '' || $buyer->id != '') {
                \session()->put('buyer_id', $buyer->id);

                // This is your test secret API key.



                // return view('contents.stripeCheckout');
            }
            return view('contents.stripeCheckout');

            //return response()->redirectTo(route('checkout.show'))->with('buyer', $buyer);
        } else {
            return redirect()->route('index');
        }
    }
    public function stripeCheckout(Request $request)
    {
        return view('contents.stripeCheckout');
    }
}
