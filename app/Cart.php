<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'status'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'cart_products');
    }
}
