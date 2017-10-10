<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'description',
        'discount',
        'max_uses',
        'expires_at'
    ];
}
