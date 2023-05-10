<?php

use Gloudemans\Shoppingcart\Facades\Cart;

function getNumbers()
{
    $nonTaxableItemSum = 0;
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? '';
    $newSubtotal = (float)Cart::instance('default')->subTotal() - (float)$discount;
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTax = ($newSubtotal - $nonTaxableItemSum) * $tax;

    $newTotal = $nonTaxableItemSum + (($newSubtotal - $nonTaxableItemSum) * (1 + $tax));

    return collect([
        'subtotal' => (float) Cart::instance('default')->subTotal() ?? 0.00,
        'code' => $code,
        'discount' => $discount,
        'newSubtotal' => $newSubtotal,
        'tax' => $tax,
        'newTax' => $newTax,
        'newTotal' => $newTotal
    ]);
}

function presentPrice($price)
{
    // return $price ;
    //    setlocale(LC_MONETARY, 'en_US');
    // return money_format('$ %i', (float) $price);
    return "$ " . number_format((float)$price, 2);
}
// Get Order 
function customProduct($order)
{
    
    $orderProducts = \App\Models\OrderProduct::where('order_id', $order->id)->get();
     Log::info('chage.succeeded', [$orderProducts]);
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
    curl_close($curl);
}

function getCartCount()
{
    return Cart::instance('default')->count();
}

