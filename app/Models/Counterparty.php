<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counterparty extends Model
{
    protected $fillable = [
        'name', 'iin', 'kbe', 'iik', 'bank_name', 'bik', 'address', 'manager', 'phone', 'email'
    ];

    public function productReceipts()
    {
        return $this->hasMany(ProductReceipt::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
