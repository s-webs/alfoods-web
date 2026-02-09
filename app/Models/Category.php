<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'image', 'name', 'slug', 'sort_order', 'color_from', 'color_to', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
