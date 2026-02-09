<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'new_name', 'barcode', 'images', 'description', 'unit', 'slug', 'purchase_price', 'price', 'discount_price', 'stock', 'specs', 'meta', 'is_active'
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
        'purchase_price' => 'float',
        'price' => 'float',
        'discount_price' => 'float'
    ];
}
