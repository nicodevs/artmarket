<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'cover', 'thumbnail'];

    /**
     * The images have many categories.
     */
    public function images()
    {
        return $this->belongsToMany('App\Image');
    }
}
