<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'description',
        'url',
        'contest_id',
        'tags',
        'visibility',
        'featured',
        'extra',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
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
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    /*
    public function save(array $options = [])
    {
        parent::save();
        dd($this->categories_ids);
        if (isset($this->categories_ids)) {
            $this->categories()->sync($this->categories_ids);
        }
    }
    */
}
