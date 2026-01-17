<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'product_title',
        'product_price',
        'product_image',
        'quantity'
    ];
}
