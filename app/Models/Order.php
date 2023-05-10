<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['billing_email','billing_name','billing_address','billing_city','billing_province','billing_state','billing_country','billing_postalcode','billing_phone','billing_name_on_card','billing_discount','billing_discount_code','transection_id','checkout_id','receipt_url','exp_month','exp_year','last4','billing_base_total','billing_subtotal','billing_tax','billing_total','ship_different_address','diff_name','diff_address','diff_province','diff_state','diff_city','diff_postalcode','order_notes','payment_gateway','shipped','paypal_id','fulfillment_count','amazon_user_id','amazon_order_reference_id','status','error','deleted_at','updated_at','created_at'];
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'variant', 'months', 'price', 'type', 'total_price');
    } 

}
