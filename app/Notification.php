<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'image_id',
        'type',
        'description',
        'visualized',
        'mailed'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'image_id' => 'integer',
        'visualized' => 'boolean',
        'mailed' => 'boolean'
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

    /**
     * Composes a new notification.
     *
     * @param string $type
     * @param App\User $recipient
     * @param App\Image $image
     * @param mixed $extra
     * @return boolean
     */
    public function compose($type, $recipient, $image, $extra)
    {
        switch ($type) {
            case 'COMMENT':
                $description = '<a href="/author/' . $extra->user->username . '">' . $extra->user->first_name . '</a> dej&oacute; un comentario en tu imagen <a href="/images/' . $image->id . '">' . $image->name . '</a>: ' . $extra->text;
                break;
        }

        return $this->create([
            'type' => $type,
            'user_id' => $recipient->id,
            'image_id' => $image->id,
            'description' => $description
        ]);
    }
}
