<?php

namespace App\Mail\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\Order;
use App\Models\Order;

class OrderPlaced extends Mailable {

    use Queueable,
        SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order) {
        $this->order = $order;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->markdown('email.admin.orderPlaced')->subject('Order Received');
    }

}
