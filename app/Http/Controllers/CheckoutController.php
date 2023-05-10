<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Cart as CartModel;
use App\Models\User;
use App\Models\Product_variants;
use Illuminate\Support\Facades\Mail;
use App\Mail\admin\OrderPlaced;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Helpers;
use Session;
use Illuminate\Support\Facades\Log;
class CheckoutController extends Controller
{
    public function cartIndex() {
        if (Cart::instance('default')->count()) {
            //echo $this->getNumbers()->get('newSubtotal');
           // echo $this->getNumbers()->get('subtotal');
            //die;
            //echo getNumbers()->get('newSubtotal');
            //die;
            return view('cart')->with([
                        'newTax' => getNumbers()->get('newTax'),
                        'subtotal' => getNumbers()->get('subtotal'),
                        'newSubtotal' => getNumbers()->get('newSubtotal'),
                        'discount' => getNumbers()->get('discount'),
                        'newTotal' => getNumbers()->get('newTotal'),
                        'billing_discount' => getNumbers()->get('discount'),
                        'billing_discount_code' => getNumbers()->get('code'),
                            ]
            );
        } else {
            return redirect()->route('index');
        }
    }

    public function store(Product $product, Request $request) {
        //session()->forget('coupon');
        $varientId = @($request->varient) ?? 0;
        //$request->varient;
        
        if (@$varientId) {
            $varient = Product_variants::where("id", $varientId)->first();
            $varient_product = [];
            $varient_product = [
                'product_id' => $product->id,
                'variant_id' => $varient->id,
                'variant_name' => $varient->name,
                'varient_price' => $varient->price,
                'varient_total_price' => $varient->total_price,
                'varient_months' => $varient->months,
                'varient_save' => $varient->save,
                'varient_save_text' => $varient->save_text,
                'varient_tablets' => $varient->tablets,
                'varient_type' => $varient->type,
                'varient_product_id'=> $varient->productID,
                'varient_price_id' => $varient->priceID

            ];
            Cart::instance('default')->add($product->id, $product->name, $request->quantityCount, $varient->total_price,0, $varient_product )->associate('App\Product');
        } else {
            Cart::instance('default')->add($product->id, $product->name, $request->quantityCount, $product->price)
                    ->associate('App\Product');
        }
        //$newdisc = session()->get('coupon')['name'];
        if (isset(session()->get('coupon')['name'])) {
            $coupon = Coupon::where('code', session()->get('coupon')['name'])->first();
            session()->put('coupon', [
                'name' => session()->get('coupon')['name'],
                'discount' => @$coupon->discount(Cart::instance('default')->subtotal()),
            ]);
        }
        return redirect()->route('cart.index')->with('success_message', 'Item is added to your cart!');
    }

    public function destroy($id)
    {
        $cart = Cart::content()->where('rowId', $id);
        if ($cart->isNotEmpty()) {
        Cart::instance('default')->remove($id);
        $newdisc = session()->get('coupon')['name'] ?? '';
        if (@$newdisc) {
            $coupon = Coupon::where('code', @$newdisc)->first();
            session()->put('coupon', [
                'name' => @$newdisc,
                'discount' => @$coupon->discount(Cart::instance('default')->subtotal()),
            ]);
        }
        return redirect()->route('cart.index')->with('success_message', "Item removed from cart");
      } else{
            return redirect()->route('cart.index')->with('success_message', 'Item removed from cart');
      }
    }

    // Update Cart items
    public function update(Request $request, $id)
    {  
        $newdisc = session()->get('coupon')['name'] ?? '';
        Cart::instance('default')->update($id, $request->quantity);
        if ($newdisc) {
            $coupon = Coupon::where('code', $newdisc)->first();
            session()->put('coupon', [
                'name' => @$newdisc,
                'discount' => @$coupon->discount(Cart::instance('default')->subtotal()),
            ]);
        }
        session()->flash('success_message', 'Quantity was updated successfully!');
        return response()->json(['success' => true]);
    }

    public function checkoutDetails(){
        if (Cart::instance('default')->count()) {
        return view('checkout-new')->with(
            [
                //'title' => 'Checkout',
                'subtotal' => getNumbers()->get('subtotal'),
                'newSubtotal' => getNumbers()->get('newSubtotal'),
                'discount' => getNumbers()->get('discount'),
                'newTotal' => getNumbers()->get('newTotal'),
                'billing_discount' => getNumbers()->get('discount'),
                'billing_discount_code' => getNumbers()->get('code'),
            ]
        );

     } else{
        return redirect()->route('index');
     }

    }
    
    // Continue Payment 
    public function continuePayment(Request $request){



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

        //$validatedData['password'] = bcrypt($validatedData['password']);
        //$user = User::create($validatedData);
        $user = User::where('email', '=',$request->email)->first();
        if($user === null){

        } else{
            $user = User::create([
                'name' => $request->fname ." ". $request->fname,
                'email' => $request['email'],
                'password' => Hash::make('123456'),
            ]);

        }

        echo "<pre>";
        print_r($user);
        echo "</pre>";
        die;
        return back()->with('success', 'User created successfully.');

    }

    public function thankYou(){
           if(!empty(session()->get('customer_id'))){
            Session()->forget('coupon');
            Session()->forget('customer_id');
            Cart::instance('default')->destroy();
            return view('thank-you');
           } else{
             return redirect()->route('index');
           }    
    }
    public function thankYouOld(){

        //dd($_REQUEST); 
        if (Cart::instance('default')->count()) {
        $custome_id = base64_decode($_GET['customer_id']);
        //$order = Order::where('checkout_id',$checkout_id)->first(); 
        $buyer_data = Buyer::find($custome_id);
        
        /*Order::where('id', $order->id)->update([
                'billing_name' => $buyer_data->fname,
                'billing_address' => $buyer_data->street,
                'billing_city' => $buyer_data->city,
                'billing_province' => $buyer_data->apartment,
                'billing_state' => $buyer_data->state,
                'billing_postalcode' => $buyer_data->zip,
                'billing_country' => $buyer_data->country,
                'billing_phone' => $buyer_data->phone,
        ]);*/ 
        $quantity = 0;
        $total_amount = 0.00;
        foreach (Cart::instance('default')->content() as $k => $item) {
            /*echo "<pre>";
            print_r($item);
            echo "</pre>";*/ 
           $quantity = $item->qty;
           $total_price = $item->options->varient_total_price;
           $total_amount = $total_amount + ($total_price*$quantity);
           // echo $item->options->varient_price*$quantity;
           // $total_amount = $total_amount + 
             //   echo $item->options->varient_price*$quantity;

        } 
        //$totalamount = $quantity*$total_amount;
        if(!empty(\session()->get('coupon'))){
            $billing_discount = \session()->get('coupon')['discount'];
            $billing_discount_code = \session()->get('coupon')['name'];
        } else{
            $billing_discount = '';
            $billing_discount_name = '';
        }
        $order = Order::create([
                'billing_email' => $buyer_data->email,
                'billing_name' => $buyer_data->fname,
                'billing_address' => $buyer_data->street,
                'billing_city' => $buyer_data->city,
                'billing_province' => $buyer_data->apartment,
                'billing_state' => $buyer_data->state,
                'billing_postalcode' => $buyer_data->zip,
                'billing_country' => $buyer_data->country,
                'billing_phone' => $buyer_data->phone,
                'billing_name_on_card'=> $buyer_data->fname,
                'receipt_url'=> '',
                'billing_total'=> $total_amount,
                //'billing_discount'=> $billing_discount,
                //'billing_discount_code'=> $billing_discount_code,
                'checkout_id' => '',
                'error'=> 'no'

            ]);

         $total_items_regular = 0;
        $total_items_mega = 0;

        foreach (Cart::instance('default')->content() as $k => $item) {
            
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->options->product_id,
                'quantity' => $item->qty,
                'variant' => $item->options->variant_name,
                'price' => $item->options->varient_price,
                'total_price' => $item->options->varient_total_price,
                'months' => $item->options->varient_months,
                'type' => $item->options->varient_type
            ]); 
           if($item->options->varient_type=="mega"){
                    $total_items_mega = $total_items_mega + $item->options->varient_months * $item->qty;
           }
           else{
                     $total_items_regular = $total_items_regular + $item->options->varient_months * $item->qty;
            } 
        }
            $order = Order::find($order->id);
            $order->status = 'processing';
            $order->billing_email = $buyer_data->email;
            $order->billing_name = $buyer_data->fname;
            $order->diff_name = $buyer_data->fname;
            $order->billing_address = $buyer_data->street;
            $order->diff_address = $buyer_data->street ;
            $order->diff_city = $buyer_data->city;
            $order->billing_city = $buyer_data->city;
            $order->diff_state = $buyer_data->state;
            $order->billing_state = $buyer_data->state;
            $order->diff_postalcode = $buyer_data->zip;
            $order->billing_postalcode = $buyer_data->zip;
            $order->billing_country = $buyer_data->country;
            $order->paypal_id = '';
            $order->payment_gateway = "Stripe";
            $order->save();
            $orderProducts = OrderProduct::where('order_id', $order->id)->get();
            customProduct($order);
            if ($order->status == "processing") {
                $total = 0;
                //            foreach ($orderProducts as $totalitems) {
                //                $total += $totalitems->quantity * $totalitems->months;
                //            }
                $orders = Order::where("fulfillment_count", '!=', "")->pluck('fulfillment_count');
                $order_number = [];
                foreach ($orders as $k => $order_count) {
                    $order_number[$k] = '739';
                }
                $count_fulfillment = 'HGM739';
                //            }
                if (@$order->status == 1) {
                    
                } else {
                    
                    $order->fulfillment_count = $count_fulfillment;
                    $order->shipped = 1;
                    $order->save();
                    
                    Cart::destroy();
                    session()->forget('buyer_id');
                    session::forget('order_id');
                    session()->forget('coupon');
                    //CartModel::where('email', '=', $order->billing_email)->delete();
                }
                $messages = 1;
                //            Commenting the code for disabling the amazonfullfillment order
                return view('thank-you')->with([
                    'order' => $order,
                    'orderProducts'=> $orderProducts,
                    'messages' => $messages
                ]);
                //return view('pages.thankyou', compact('order', 'orderProducts', 'messages'));
            } else if ($order->shipped == 1 && $order->status == "processing") {
                $messages = $order->shipped;
                return view('thank-you')->with([
                    'order' => $order,
                    'orderProducts' => $orderProducts,
                    'messages' => $messages
                ]);
            } else if ($order->status != "pending") {
                $messages = $order->shipped;
                return view('thank-you')->with([
                    'order' => $order,
                    'orderProducts' => $orderProducts,
                    'messages' => $messages
                ]);
            } else {
                return redirect()->route('index');
            }
            
            // Mail::to(env("ORDER_EMAIL", 'karan@brandsonify.com'))->send(new OrderPlaced($order));
    } else{
        return redirect()->route('index');
    }
    //echo "Thank you mam."; 
    }

    public function cancel(){
        return view('cancel-checkout');
    }
    // Create Drip(custom button)
    public function createOrder(){
        return view('create-order-new');
    }
    
    public function createDrip(){
    
    $order = \App\Models\Order::where('id', 661)->first();
    //dd($order);
    $orderProducts = \App\Models\OrderProduct::where('order_id', $order->id)->get();
    //dd( $orderProducts);
    $total_items_mega = 0;
    $total_items_regular = 0;
    foreach ($orderProducts as $totalitems) {
        if ($totalitems->type == "regular") {
            $total_items_regular = $total_items_regular + $totalitems->months * $totalitems->quantity;
        } else {
            $total_items_mega = $total_items_mega + $totalitems->months * $totalitems->quantity;
        }
        //        $total += $totalitems->quantity * $totalitems->months;
    }
    $name = "Horny Goat Max ";
    if ($total_items_regular > 0) {
        $name = $name . " / Regular " . $total_items_regular;
    }
    if ($total_items_mega > 0) {
        $name = $name . " / Mega " . $total_items_mega;
    }
    $order_date = $order->created_at;
    $fulfillment_count = $order->fulfillment_count;
    $order_format = $order_date->toIso8601String();
    $order_request = $order->status;
    $final_order_id = '';
    $drip_status = '';
    if ($order_request == 'processing') {
        $drip_status = 'placed';
    }
    if ($order_request == 'canceled') {
        $drip_status = 'canceled';
    }
    if ($order_request == 'returned') {
        $drip_status = 'refunded';
    }
    if ($order_request == 'complete') {
        $drip_status = 'fulfilled';
    }
    if (@$fulfillment_count) {
        $final_order_id = $fulfillment_count;
    } else {
        $final_order_id = $order->id;
    }
    $billing_discount = $order->billing_discount;
    $billingdiscount  = number_format((float)$billing_discount, 2, '.', '');
    $order_total = (float)$order->billing_total - (float)$billingdiscount;
    $order_total = number_format((float)$order_total, 2, '.', '');
    $billing_total = number_format((float)$order->billing_total, 2, '.', '');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://0f5857da4f8b0a71cd4b90c3704e3841@api.getdrip.com/v3/5182951/shopper_activity/order",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"provider\": \"bulletproofnaturals.com\",
            \"email\": \"$order->billing_email\",
            \"action\": \"$drip_status\",
            \"occurred_at\": \"$order_format\",
            \"order_id\": \"$final_order_id\",
            \"order_public_id\": \"$final_order_id\",
            \"grand_total\": $order_total,
            \"discount\": $billingdiscount,
            \"currency\": \"USD\",
            \"order_url\": \"https://bulletproofnaturals.com/thankyou/$order->id\",
            \"items\": [  {    
              \"product_id\": \"1\",    
              \"product_variant_id\": \"TW-BHER-94M2\",
              \"sku\": \"TW-BHER-94M2\",
              \"name\": \"$name\", 
              \"brand\": \"Bulletproofnaturals\",
              \"categories\": [\"Capsules\"],
              \"price\": $billing_total,
              \"quantity\": 1,             
              \"discount\": $billingdiscount,                   
              \"total\": $billing_total,    
              \"product_url\": \"https://bulletproofnaturals.com/product/horny-goat-max/1-bottle\",
              \"image_url\": \"https://bulletproofnaturals.com/storage/JxfkFuM2Uw32wkXfvydfvT32v75MdPo5N4tOmjFz.jpg\",
              \"product_tag\": \"Horny Goat Max\"  }],
              \"billing_address\": {  \"label\": \"Billing Address\",
              \"first_name\": \"$order->billing_name\",
              \"address_1\": \"$order->billing_address\",
              \"city\": \"$order->billing_city\",
              \"state\": \"$order->billing_state \",
              \"postal_code\": \"$order->billing_postalcode\",
              \"country\": \"$order->billing_country\"},
              \"shipping_address\": {  
              \"label\": \"Shipping Address\",
              \"first_name\": \"$order->diff_name\",
              \"address_1\": \"$order->diff_address\",
              \"city\": \"$order->diff_city\",
              \"state\": \"$order->diff_state\",
              \"postal_code\": \"$order->diff_postalcode\",
              \"country\": \"$order->billing_country\"}\r\n }",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "User-Agent: Your App Name (www.yourapp.com)"
        ),
    ));
    $response = curl_exec($curl);
    
    if (curl_errno($curl)) {
        $error = array('error'=> curl_error($ch1));
        Log::info('Response Error ',$error);
    } else{
        $success = array('success'=> $response);
        Log::info('Response Success ',$success);
    }
    curl_close($curl);
    }    

}
