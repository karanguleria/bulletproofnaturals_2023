<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonial;
use App\Faq;
use App\Product;
use App\ProductVariant;
use App\Contact;
use App\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\admin\ContactUs;
use TCG\Voyager\Models\Menu;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Session\SessionManager;
use App\Helpers;
use Log;

class HomeController extends Controller {

    public function home(SessionManager $session) {
        $data = array(
            'page' => 'home',
        );
        $data = array_merge($data, $this->globalDetails());
        return response()->json($data);
    }

    public function thanks() {
        return view('thanks');
    }

    public function subscribe() {
        return view('subscribe');
    }

    protected function globalDetails() {
        // dd(Cart::instance('default'));
        // $footerMenuShop = Menu::display('footer', 'api.menu');
        // $footerMenuSupport = Menu::display('supportfooter', 'api.menu');
        // $header = Menu::display('Header Home', 'api.menu');
        return array(
            'logo' => 'https://bulletproofnaturals.com/images/logo.png',
            'tag_line' => 'FREE SHIPPING ON ALL ORDERS',
            'cart_icon' => asset('images/cart.png'),
            //'cart_link' => (@Cart::instance('default')->count()) ? route('checkout.index') : route('product.show', ['product' => 'horny-goat-max', 'varienId' => '1-bottle']),
            //'cart_count' => getCartCount(),
            //'footerMenuShop' => json_decode($footerMenuShop, true),
            //'footerMenuSupport' => json_decode($footerMenuSupport, true),
            //'header' => json_decode($header, true),
            'footer_logo' => 'https://bulletproofnaturals.com/images/bulletprooffooter.png',
            'footer_logo_link' => 'https://bulletproofnaturals.com/',
            'social_network' => array(
                "facebook" => "https://www.facebook.com/BulletproofNaturals/",
                "twitter" => "https://twitter.com/bulletproofnats",
                'instagram' => "https://www.instagram.com/bulletproofnaturals/",
            ),
            'footer_links' => array(
                'privacy' => 'Privacy Policy',
                'privacy_link' => 'https://bulletproofnaturals.com/privacy-policy',
                'terms_condition' => 'Terms and Conditions',
                'terms_condition_link' => 'https://bulletproofnaturals.com/terms-and-conditions'
            ),
            'copyright' => '<span class="w_msg">†</span>Statements made on this website have not been evaluated by the U.S. Food and Drug Administration. These products are not intended to diagnose, treat, cure, or prevent any disease. Information provided by this website or this company is not a substitute for individual medical advice. &nbsp;<span class="copyright">©Bulletproof Naturals, LLC 2019-2023</span>',
        );
    }

    protected function cartInfo() {
        return array(
            'cart_link' => (@Cart::instance('default')->count()) ? route('cart.index') : route('product.show', ['product' => 'horny-goat-max', 'varienId' => '1-bottle']),
            'cart_count' => getCartCount()
        );
    }
//     public function dripTest() {
//         echo "Method Save"."<Br>";
//         $i = date("His");
//         $order = \App\Models\Order::where('id',352)->first();
//         
//            $order->status = 'processing';
//            $order->billing_email = 'yash@brandsonify.com';
//            $order->billing_name = "Developer";
//            $order->diff_name = "Developer";
//            $order->billing_address = "test";
//            $order->diff_address = "test";
//            $order->diff_city = "test";
//            $order->billing_city = "test";
//            $order->diff_state = "test";
//            $order->billing_state = "test";
//            $order->diff_postalcode = "12345";
//            $order->billing_postalcode = "12345";
//            $order->billing_country = "usa";
//            $order->paypal_id = '';
//            $order->payment_gateway = "Stripe";
////            dd("helo");
//
//            $order->save();
//            echo "Order Save"."<Br>";
//            
////            $orderProducts = \App\Models\OrderProduct::where('order_id', $order->id)->get();
//            echo "Calling Drip"."<Br>";
////            customProduct($order);
//            
//            $orderProducts = \App\Models\OrderProduct::where('order_id', $order->id)->get();
//            $total_items_mega = 0;
//            $total_items_regular = 0;
//            foreach ($orderProducts as $totalitems) {
//                if ($totalitems->type == "regular") {
//                    $total_items_regular = $total_items_regular + $totalitems->months * $totalitems->quantity;
//                } else {
//                    $total_items_mega = $total_items_mega + $totalitems->months * $totalitems->quantity;
//                }
//                //        $total += $totalitems->quantity * $totalitems->months;
//            }
//            $name = "Horny Goat Max ";
//            if ($total_items_regular > 0) {
//                $name = $name . " / Regular " . $total_items_regular;
//            }
//            if ($total_items_mega > 0) {
//                $name = $name . " / Mega " . $total_items_mega;
//            }
//            
//            $order_date = $order->created_at;
//            $fulfillment_count = "TESTORDER".$i;
//            $order_format = $order_date->toIso8601String();
//            $order_request = $order->status;
//            $final_order_id = '';
//            $drip_status = '';
//            if ($order_request == 'processing') {
//                $drip_status = 'placed';
//            }
//            if ($order_request == 'canceled') {
//                $drip_status = 'canceled';
//            }
//            if ($order_request == 'returned') {
//                $drip_status = 'refunded';
//            }
//            if ($order_request == 'complete') {
//                $drip_status = 'fulfilled';
//            }
//            if (@$fulfillment_count) {
//                $final_order_id = $fulfillment_count;
//            } else {
//                $final_order_id = $order->id;
//            }
//            $billing_discount = $order->billing_discount;
//            $billingdiscount  = number_format((float)$billing_discount, 2, '.', '');
//            $order_total = (float)$order->billing_total - (float)$billingdiscount;
//            $order_total = number_format((float)$order_total, 2, '.', '');
//            $billing_total = number_format((float)$order->billing_total, 2, '.', '');
//            $curl = curl_init();
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => "https://0f5857da4f8b0a71cd4b90c3704e3841@api.getdrip.com/v3/5182951/shopper_activity/order",
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_POSTFIELDS => "{
//                    \"provider\": \"bulletproofnaturals.com\",
//                    \"email\": \"$order->billing_email\",
//                    \"action\": \"$drip_status\",
//                    \"occurred_at\": \"$order_format\",
//                    \"order_id\": \"$final_order_id\",
//                    \"order_public_id\": \"$final_order_id\",
//                    \"grand_total\": $order_total,
//                    \"discount\": $billingdiscount,
//
//                    \"currency\": \"USD\",
//                    \"order_url\": \"https://bulletproofnaturals.com/thankyou/$order->id\",
//                    \"items\": [  {    
//                      \"product_id\": \"1\",    
//                      \"product_variant_id\": \"TW-BHER-94M2\",
//                      \"sku\": \"TW-BHER-94M2\",
//                      \"name\": \"$name\", 
//                      \"brand\": \"Bulletproofnaturals\",
//                      \"categories\": [\"Capsules\"],
//                      \"price\": $billing_total,
//                      \"quantity\": 1,             
//                      \"discount\": $billingdiscount,                   
//                      \"total\": $billing_total,    
//                      \"product_url\": \"https://bulletproofnaturals.com/product/horny-goat-max/1-bottle\",
//                      \"image_url\": \"https://bulletproofnaturals.com/storage/JxfkFuM2Uw32wkXfvydfvT32v75MdPo5N4tOmjFz.jpg\",
//                      \"product_tag\": \"Horny Goat Max\"  }],
//                      \"billing_address\": {  \"label\": \"Billing Address\",
//                      \"first_name\": \"$order->billing_name\",
//                      \"address_1\": \"$order->billing_address\",
//                      \"city\": \"$order->billing_city\",
//                      \"state\": \"$order->billing_state \",
//                      \"postal_code\": \"$order->billing_postalcode\",
//                      \"country\": \"$order->billing_country\"},
//                      \"shipping_address\": {  
//                      \"label\": \"Shipping Address\",
//                      \"first_name\": \"$order->diff_name\",
//                      \"address_1\": \"$order->diff_address\",
//                      \"city\": \"$order->diff_city\",
//                      \"state\": \"$order->diff_state\",
//                      \"postal_code\": \"$order->diff_postalcode\",
//                      \"country\": \"$order->billing_country\"}\r\n }",
//                CURLOPT_HTTPHEADER => array(
//                    "Content-Type: application/json",
//                    "User-Agent: Your App Name (www.yourapp.com)"
//                ),
//            ));
//            $response = curl_exec($curl);
////            Log::info('Response Error ',$response);
//            echo "<pre>";
//            print_r($response);
//            echo "</pre>";
//            if (curl_errno($curl)) {
//                $error = array('error'=> curl_error($ch1));
////                Log::info('Response Error ',$error);
//            } else{
//                $success = array('success'=> $response);
////                Log::info('Response Success ',$success);
//            }
//            curl_close($curl);
//            curl_close($curl);
//    
//    
//            echo "Drip Called"."<Br>";
////        customProduct($order);
//    }

}
