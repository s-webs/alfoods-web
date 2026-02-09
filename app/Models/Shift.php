<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'opened_at', 'closed_at'
    ];

    protected array $dates = ['opened_at', 'closed_at'];
}
