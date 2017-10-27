<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
        'results_count',
        'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'results_count' => 'integer'
    ];

    /**
     * The user that the comment belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
