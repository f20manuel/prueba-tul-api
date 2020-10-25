<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'SKU',
        'description',
    ];

    public function carts()
    {
        return $this->belongsToMany('App\Cart', 'cart_products');
    }
}
