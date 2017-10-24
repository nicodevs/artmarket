<?php

namespace App\Http\Controllers;

use App\Events\ImageApproved;
use App\Exceptions\InvalidUploadedFileException;
use App\Http\Resources\Image as ItemResource;
use App\Http\Resources\Images as CollectionResource;
use App\Image;
use App\ImageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * The rules for storing and updating according to the role.
     *
     * @var array
     */
    private $rules = [
        'store' => [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'url' => 'required',
            'tags' => 'required',
            'url_disc' => 'sometimes',
            'gravity' => 'sometimes',
            'categories' => 'required|array',
            'contest_id' => 'sometimes|integer|exists:contests,id'
        ],
        'update' => [
            'user' => [
                'name' => 'sometimes|max:50',
                'description' => 'sometimes|max:255',
                'tags' => 'sometimes',
                'contest_id' => 'sometimes|integer|exists:contests,id',
                'categories' => 'sometimes',
                'gravity' => 'sometimes'
            ],
            'admin' => [
                'status' => 'sometimes',
                'url_disc' => 'sometimes',
                'url_cutoff' => 'sometimes',
                'visibility' => 'sometimes',
                'featured' => 'sometimes|boolean',
                'extra' => 'sometimes|json'
            ]
        ]
    ];

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Image $image)
    {
        return new CollectionResource($image->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ImageFile  $imageFile
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ImageFile $imageFile)
    {
        $data = $request->validate($this->getValidationRules('store'));

        $image = $this->saveImageFiles(new Image($data), $request);

        $image = auth()->user()->images()->save($image);

        $image->saveCategories($data);

        return new ItemResource($image);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return new ItemResource($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @param  \App\ImageFile  $imageFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImageFile $imageFile, Image $image)
    {
        $this->checkUserAccess($image);

        $data = $request->validate($this->getValidationRules('update'));

        $image = $this->saveImageFiles($image, $request);

        $image->fill($data);

        $this->controlStatusChange($image);

        $image->save();

        $image->saveCategories($data);

        return new ItemResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $this->checkUserAccess($image);

        $image->delete();
        return ['success' => true];
    }

    /**
     * Returns the validation rules.
     *
     * @return array
     */
    protected function getValidationRules($action)
    {
        if ($action === 'store') {
            return $this->rules['store'];
        }

        $rules = $this->rules['update']['user'];
        if (auth()->user()->admin) {
            $rules = array_merge($rules, $this->rules['update']['admin']);
        }

        return $rules;
    }

    /**
     * Saves the uploaded images and move the image files.
     *
     * @param App\Image $image
     * @param  array  $data
     * @return App\Image
     */
    protected function saveImageFiles($image, $data)
    {
        foreach (['frame', 'disc', 'cutoff'] as $type) {

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
                if ($type === 'disc' && $data[$url] === 'AUTO') {

                    $max = min([$image->width, $image->height]);
                    $image->setAttribute($width, $max);
                    $image->setAttribute($height, $max);

                } else {

                    $file = ImageFile::where('filename', '=', $data[$url])->first();
                    if (!$file) {
                        throw new InvalidUploadedFileException;
                    }

                    $image->setAttribute($width, $file->width);
                    $image->setAttribute($height, $file->height);

                    if ($type === 'frame') {
                        $image->setAttribute('orientation', $file->orientation);
                    }

                    $file->delete();
                    Storage::disk('uploads')->move('temporal/' . $file->filename, 'originals/' . $file->filename);
                }
            }
        }

        return $image;
    }

    /**
     * Fires events for image changes
     *
     * @param App\Image $image
     * @return void
     */
    public function controlStatusChange($image)
    {
        if ($image->isDirty('status') && ($image->status === 'APPROVED')) {
            event(new ImageApproved($image));
        }
    }
}
