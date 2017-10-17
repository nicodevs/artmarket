<?php

namespace App\Http\Controllers;

use App\ImageFile;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Item as ItemResource;
use Illuminate\Validation\Rule;

class ImageFileController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => [
                'required',
                'image',
                'min:1000',
                'max:15000',
                Rule::dimensions()->minWidth(1276)->minHeight(1276)
            ]
        ]);

        $size = $request->image->getSize();
        $extension = $request->image->getClientOriginalExtension();
        $mimeType = $request->image->getMimeType();
        list($width, $height) = getimagesize($request->image);

        $filename = md5(uniqid()) . '.' . $extension;
        $path = $request->image->storeAs('temporal', $filename, 'uploads');

        $file = ImageFile::create([
            'filename' => $filename,
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'orientation' => $width > $height? 'landscape' : 'portrait'
        ]);

        return new ItemResource($file);
    }
}
