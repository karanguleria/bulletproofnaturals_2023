<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;

class PaymentController extends Controller {

    public $gateway;

    public function __construct() {
        $this->gateway = Omnipay::create('PayPal_Pro');
        $this->gateway->setUsername(env('PAYPAL_API_USERNAME'));
        $this->gateway->setPassword(env('PAYPAL_API_PASSWORD'));
        $this->gateway->setSignature(env('PAYPAL_API_SIGNATURE'));
        $this->gateway->setTestMode(true); // here 'true' is for sandbox. Pass 'false' when go live
    }

    public function index() {
        return view('payment');
    }

    public function charge(Request $request) {
        $arr_expiry = explode("/", $request->input('expiry'));

        $formData = array(
            'firstName' => $request->input('first-name'),
            'lastName' => $request->input('last-name'),
            'number' => $request->input('number'),
            'expiryMonth' => trim($arr_expiry[0]),
            'expiryYear' => trim($arr_expiry[1]),
            'cvv' => $request->input('cvc')
        );

        try {
            // Send purchase request
            $response = $this->gateway->purchase([
                        'amount' => $request->input('amount'),
                        'currency' => 'USD',
                        'card' => $formData
                    ])->send();

                // Process response
            if ($response->isSuccessful()) {
                // Payment was successful
                $arr_body = $response->getData();
                $amount = $arr_body['AMT'];
                $currency = $arr_body['CURRENCYCODE'];
                $transaction_id = $arr_body['TRANSACTIONID'];

                echo "Payment of $amount $currency is successful. Your Transaction ID is: $transaction_id";
            } else {
                // Payment failed
                echo "Payment failed. " . $response->getMessage();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}