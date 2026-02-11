<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const UNIT_PCS = 'pcs';
    public const UNIT_GRAM = 'g';

    protected $fillable = [
        'category_id', 'name', 'new_name', 'barcode', 'images', 'description', 'unit', 'slug', 'purchase_price', 'price', 'discount_price', 'stock', 'stock_threshold', 'specs', 'meta', 'is_active'
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
        'purchase_price' => 'float',
        'price' => 'float',
        'discount_price' => 'float',
        'stock' => 'float',
        'stock_threshold' => 'float',
    ];

    /**
     * Сохраняем остаток как float без округления (для граммовых товаров 1.25 и т.д.).
     */
    public function setStockAttribute($value): void
    {
        $this->attributes['stock'] = $value === null ? 0 : (float) $value;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
