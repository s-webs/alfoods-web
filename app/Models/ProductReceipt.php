<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReceipt extends Model
{
    protected $fillable = [
        'counterparty_id', 'supplier_name', 'items', 'total_qty', 'total_price'
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'float'
    ];

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }
}
