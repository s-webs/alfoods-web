<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $fillable = [
        'sale_id', 'counterparty_id', 'amount', 'payment_date', 'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'payment_date' => 'datetime',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }
}
