<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSet extends Model
{
    protected $fillable = [
        'name', 'slug', 'barcode', 'price', 'discount_price', 'is_active', 'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
        'price' => 'float',
        'discount_price' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(ProductSetItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_set_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getEffectivePriceAttribute(): float
    {
        return ($this->discount_price !== null && $this->discount_price > 0)
            ? (float) $this->discount_price
            : (float) $this->price;
    }
}
