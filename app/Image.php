<?php

namespace App;

use App\Exceptions\InvalidUploadedFileException;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'description',
        'url',
        'url_disc',
        'contest_id',
        'tags',
        'visibility',
        'featured',
        'extra',
        'gravity',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'contest_id' => 'integer',
        'height' => 'integer',
        'height_cutoff' => 'integer',
        'height_disc' => 'integer',
        'width' => 'integer',
        'width_cutoff' => 'integer',
        'width_disc' => 'integer',
        'featured'=> 'boolean',
        'sales'=> 'integer',
        'visits'=> 'integer',
        'random'=> 'integer'
    ];

    /**
     * The users that the image belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The categories of the image.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'categories_images');
    }

    /**
     * The comments of the image.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * The likes of the image.
     */
    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    /**
     * Adds the image to an array of categories.
     *
     * @param  array  $data
     * @return App\Image
     */
    public function saveCategories($data)
    {
        if (!isset($data['categories'])) {
            return $this;
        }

        $this->categories()->sync($data['categories']);
        return $this;
    }
}
