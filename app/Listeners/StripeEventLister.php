<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Helpers;
use App\Models\Buyer;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\Product_variants;
use Session;
class StripeEventLister
{
    
    /**
     * Handle the event.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        
        switch($event->payload['type']){
            case 'charge.succeeded':
            $this->handleChargeSucceeded($event->payload['data']);
            break;
        }

    }
    public function handleChargeSucceeded($charge){
        //Log::info($charge);
        Log::info('chage.succeeded', [$charge]);
        
        $transaction_id = $charge['object']['id'];
        $billing_address_city = ($charge['object']['billing_details']['address']['city']) ? $charge['object']['billing_details']['address']['city'] : '';
        $billing_address_country = ($charge['object']['billing_details']['address']['country']) ? $charge['object']['billing_details']['address']['country'] : '';
        $billing_address_line1 = ($charge['object']['billing_details']['address']['line1'])? $charge['object']['billing_details']['address']['line1'] : '';
        $billing_address_line2 = ($charge['object']['billing_details']['address']['line2']) ? $charge['object']['billing_details']['address']['line2'] : '';
        $billing_address_postal_code = ($charge['object']['billing_details']['address']['postal_code']) ? $charge['object']['billing_details']['address']['postal_code'] :'';
        $billing_address_state = ($charge['object']['billing_details']['address']['state']) ? $charge['object']['billing_details']['address']['state'] : '';
        $email = ($charge['object']['billing_details']['email']) ? $charge['object']['billing_details']['email'] : '';
        $name = ($charge['object']['billing_details']['name']) ? $charge['object']['billing_details']['name'] : '';
        $phone = ($charge['object']['billing_details']['phone'])? $charge['object']['billing_details']['phone'] : '';
        $receipt_url = ($charge['object']['receipt_url']) ? $charge['object']['receipt_url'] : '';
        $shipping_address_city = ($charge['object']['shipping']['address']['city']) ? $charge['object']['shipping']['address']['city'] :'';
        $shipping_address_country = ($charge['object']['shipping']['address']['country'])? $charge['object']['shipping']['address']['country']: '';
        $shipping_address_line1 = ($charge['object']['shipping']['address']['line1'])? $charge['object']['shipping']['address']['line1'] : '' ;
        $shipping_address_line2 = ($charge['object']['shipping']['address']['line2'])? $charge['object']['shipping']['address']['line2'] : '';
        $shipping_address_postal_code = ($charge['object']['shipping']['address']['postal_code']) ? $charge['object']['shipping']['address']['postal_code'] : '';
        $shipping_address_state = ($charge['object']['shipping']['address']['state']) ? $charge['object']['shipping']['address']['state']: '';
        $shipping_name = ($charge['object']['shipping']['name']) ? $charge['object']['shipping']['name']: '';
        
        $quantity = 0; 
        $total_amount = 0.00;
        $itemdata = unserialize($charge['object']['metadata']['extras']);
        $item_data = (array)$itemdata;
        $billing_base_total = $charge['object']['metadata']['billing_base_total'];
        $billing_subtotal = $charge['object']['metadata']['billing_subtotal'];
        
        $billing_discount = ($charge['object']['metadata']['billing_discount']) ? $charge['object']['metadata']['billing_discount']: 0.00;
        $billing_discount_code = ($charge['object']['metadata']['billing_discount_code'])? $charge['object']['metadata']['billing_discount_code']:'None';

        foreach ($item_data as $item) {
            $varient_data = Product_variants::find($item['variant_id']);
            $quantity = $item['quantity'];
            $total_price = (float)$varient_data->total_price;
            $total_amount = $total_amount + ($total_price * $quantity); 
        }

        $order = Order::create([
            'billing_email' => $email,
            'billing_name' => $name,
            'billing_address' => $billing_address_line1 . " ". $billing_address_line2,
            'billing_city' => $billing_address_city,
            'billing_province' => $billing_address_city,
            'billing_state' => $billing_address_state,
            'billing_postalcode' => $billing_address_postal_code,
            'billing_country' => $billing_address_country,
            'billing_phone' => $phone,
            'billing_name_on_card' => $name,
            'receipt_url' => $receipt_url,
            'billing_base_total' => $billing_base_total,
            'billing_subtotal' => $billing_subtotal,
            'billing_total' => $total_amount,
            'billing_discount'=> $billing_discount,
            'billing_discount_code'=> $billing_discount_code,
            'paypal_id' => $transaction_id,
            //'checkout_id' => '',
            'error' => 'no'

        ]);

        $total_items_regular = 0;
        $total_items_mega = 0;

        foreach ($item_data as  $item) {
            $varient_data = Product_variants::find($item['variant_id']);
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $varient_data->product_id,
                'quantity' => $item['quantity'],
                'variant' => $varient_data->name,
                'price' => (float)$varient_data->price,
                'total_price' => (float)$varient_data->total_price,
                'months' => $varient_data->months,
                'type' => $varient_data->type
            ]);
            if ($varient_data->type == "mega") {
                $total_items_mega = $total_items_mega + $varient_data->months * $item['quantity'];
            } else {
                $total_items_regular = $total_items_regular + $varient_data->months * $item['quantity'];
            }
        }

        $orders = Order::where("fulfillment_count", '!=', "")->pluck('fulfillment_count');
        $order_number = [];
        foreach ($orders as $k => $order_count) {
                $order_number[$k] = (int) str_replace("HGM", "", $order_count);
        }
        $count_fulfillment = 'HGM' . (max($order_number) + 1);

        $order = Order::find($order->id);
        $order->status = 'processing';
        $order->billing_email = $email;
        $order->billing_name = $name;
        $order->diff_name = $shipping_name;
        $order->billing_address = $billing_address_line1;
        $order->diff_address = $shipping_address_line1;
        $order->diff_city = $shipping_address_city;
        $order->billing_city = $billing_address_city;
        $order->diff_state = $shipping_address_state;
        $order->billing_state = $billing_address_state;
        $order->diff_postalcode = $shipping_address_postal_code;
        $order->billing_postalcode = $billing_address_postal_code;
        $order->billing_country = $billing_address_country;
        $order->paypal_id = $transaction_id;
        $order->payment_gateway = "Stripe";
        $order->fulfillment_count = $count_fulfillment;
        $order->shipped = 1;
        $order->save();
        customProduct($order);
//        Log::info($order);
        
        
    }
}
