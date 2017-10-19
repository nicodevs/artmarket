<?php

namespace App;

use App\Exceptions\InvalidUploadedFileException;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'text'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'image_id' => 'integer'
    ];

    /**
     * The user that the comment belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The image that the comment belongs to.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }
}
