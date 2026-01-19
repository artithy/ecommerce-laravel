<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'items',
        'total_price',
        'status'
    ];
}
