<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Contest extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'description',
        'terms',
        'cover_desktop',
        'cover_mobile',
        'prize_image_desktop',
        'prize_image_mobile',
        'winners_image_desktop',
        'winners_image_mobile',
        'expires_at'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
