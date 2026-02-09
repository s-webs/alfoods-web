<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'cashier_id', 'shift_id', 'shopper_id', 'items', 'total_qty', 'total_price'
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'float'
    ];
}
