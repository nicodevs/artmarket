<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'admin' => 'boolean',
    ];

    /**
     * The users have images.
     */
    public function images()
    {
        return $this->hasMany('App\Image');
    }

    /**
     * The users have likes.
     */
    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    /**
     * Check if the user owns the related model.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function owns($model)
    {
        return $this->id === $model->user_id;
    }

    /**
     * Likes an image.
     *
     * @param integer $imageId
     * @return App\Like
     */
    public function likesImage($imageId)
    {
        return $this->likes()->updateOrCreate(['image_id' => $imageId]);
    }

    /**
     * Dislikes an image.
     *
     * @param integer $imageId
     * @return App\Like
     */
    public function dislikesImage($imageId)
    {
        return $this->likes()->where('image_id', '=', 1)->delete();
    }

    /**
     * Creates a unique username
     *
     * @param string $email Email
     * @return string Username
     */
    public function createUsername($email)
    {
        $i = 0;
        $username = str_replace('.', '', substr($email, 0, strpos($email, '@')));

        // If the username exist, append a suffix
        while ($this->usernameExist($username)) {
            $i++;
            $username = $username . '_' . $i;
        }

        // Return unique username
        return $username;
    }

    /**
     * Tells if an username exist in the database
     *
     * @param string $username Username
     * @return boolean Returns true if the username is unique
     */
    private function usernameExist($username)
    {
        return $this->where('username', '=', $username)->exists();
    }

    /**
     * Splits a fullname
     *
     * @param array $data data
     * @return array $data Updated data
     */
    public function splitName($data)
    {
        if (preg_match('/\s/', $data['name'])) {
            $name = explode(' ', $data['name'], 2);
            $data['first_name'] = $name[0];
            $data['last_name'] = $name[1];
        } else {
            $data['first_name'] = $data['name'];
        }
        unset($data['name']);
        return $data;
    }
}
