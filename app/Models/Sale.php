<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_RETURNED = 'returned';

    protected $fillable = [
        'cashier_id', 'shift_id', 'shopper_id', 'counterparty_id', 'is_on_credit', 'paid_amount',
        'items', 'total_qty', 'total_price', 'status',
    ];

    protected $casts = [
        'items' => 'array',
        'total_price' => 'float',
        'paid_amount' => 'float',
        'is_on_credit' => 'boolean',
    ];

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function debtPayments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function getRemainingDebtAttribute(): float
    {
        if (!$this->is_on_credit) {
            return 0;
        }

        // Используем только paid_amount: при каждой оплате (pay-debt или pay-debt-bulk)
        // создаётся DebtPayment и вызывается increment('paid_amount'), поэтому
        // суммировать debtPayments->sum('amount') вместе с paid_amount — двойной учёт.
        return max(0, $this->total_price - (float) $this->paid_amount);
    }
}
