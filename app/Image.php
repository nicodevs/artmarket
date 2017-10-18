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
        'url_disk',
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
        'width' => 'integer',
        'height' => 'integer'
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

    /**
     * Moves the image file to the right folder.
     *
     * @param  array  $data
     * @param  \App\ImageFile  $imageFile
     * @return App\Image
     */
    public function saveImageFiles($data, $imageFile, $storage)
    {
        foreach (['frame', 'disk', 'cutoff'] as $type) {

            if ($type === 'frame') {
                $url = 'url';
                $width = 'width';
                $height = 'height';
            } else {
                $url = 'url_' . $type;
                $width = 'width_' . $type;
                $height = 'height_' . $type;
            }

            if (isset($data[$url])) {
                $file = $imageFile->where('filename', '=', $data[$url])->first();
                if (!$file) {
                    throw new InvalidUploadedFileException;
                }

                $this->setAttribute($width, $file->width);
                $this->setAttribute($height, $file->height);

                if ($type === 'frame') {
                    $this->setAttribute('orientation', $file->orientation);
                }

                $file->delete();
                $storage->move('temporal/' . $file->filename, 'originals/' . $file->filename);
            }
        }

        $this->save();
        return $this;
    }
}
