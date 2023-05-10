<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    protected $table = 'order_product';
    protected $fillable = ['id','order_id','product_id','quantity','variant','months','price','type','billing_phone','total_price','updated_at','created_at'];

}
