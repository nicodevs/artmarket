<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = ['name', 'description', 'thumbnail', 'border', 'border_mobile'];
}
