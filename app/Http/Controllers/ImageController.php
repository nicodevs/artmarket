<?php

namespace App\Http\Controllers;

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
            'url_disk' => 'sometimes',
            'categories' => 'required|array',
            'contest_id' => 'sometimes|integer|exists:contests,id'
        ],
        'update' => [
            'user' => [
                'name' => 'sometimes|max:50',
                'description' => 'sometimes|max:255',
                'tags' => 'sometimes',
                'contest_id' => 'sometimes|integer|exists:contests,id',
                'categories' => 'sometimes'
            ],
            'admin' => [
                'status' => 'sometimes',
                'url_disk' => 'sometimes',
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
        $data = $request->validate($this->rules['store']);

        $image = auth()->user()->images()->create($data);

        $image->saveCategories($data)->saveImageFiles($data, $imageFile, Storage::disk('uploads'));

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

        $rules = $this->rules['update']['user'];
        if (auth()->user()->admin) {
            $rules = array_merge($rules, $this->rules['update']['admin']);
        }
        $data = $request->validate($rules);

        $image = tap($image)->update($data)->saveCategories($data)->saveImageFiles($data, $imageFile, Storage::disk('uploads'));

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
}
