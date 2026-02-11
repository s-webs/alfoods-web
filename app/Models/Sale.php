<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_RETURNED = 'returned';

    protected $fillable = [
        'cashier_id', 'shift_id', 'shopper_id', 'items', 'total_qty', 'total_price', 'status',
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'float'
    ];

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
