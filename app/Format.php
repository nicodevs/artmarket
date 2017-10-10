<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $fillable = [
        'name',
        'description',
        'size',
        'type',
        'fixed',
        'enabled',
        'price',
        'cost',
        'frame_price',
        'frame_cost',
        'glass_price',
        'glass_cost',
        'pack_price',
        'pack_cost',
        'side',
        'minimum_pixels',
    ];
}
