<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';

    protected $fillable = ['id', 'email', 'f_name','l_name','street_address','appartment','city','state','zip','phone','product_id','product_name','quantity_count','price','v_id','v_name','v_price','v_total_price','v_months','v_save','v_save_text','v_tablets','created_at','updated_at']; 
}
