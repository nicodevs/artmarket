<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $fillable = [
        'filename',
        'extension',
        'mime_type',
        'size',
        'width',
        'height',
        'orientation',
    ];
}
