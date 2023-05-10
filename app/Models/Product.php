<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
     public function getRouteKeyName()
   {
       return 'slug';
   }
    /*public function media()
    {
        return $this->hasMany('App\Models\Media', 'product_id');
    }*/

    /*public function product(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'product_id');
    }*/

    public function Media()
    {
        return $this->hasMany(Media::class);
    }
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'variant', 'months', 'price', 'type', 'total_price');
    }
    
    
}
