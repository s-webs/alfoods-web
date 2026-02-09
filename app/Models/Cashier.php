<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    protected $fillable = [
        'user_id', 'name', 'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];
}
