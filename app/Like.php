<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['image_id'];

    /**
     * The likes belongs to an image.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * The likes belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
