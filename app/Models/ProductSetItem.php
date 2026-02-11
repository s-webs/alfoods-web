<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSetItem extends Model
{
    protected $fillable = [
        'product_set_id', 'product_id', 'quantity',
    ];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function productSet()
    {
        return $this->belongsTo(ProductSet::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
