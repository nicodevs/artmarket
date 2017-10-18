<?php

namespace App\Http\Controllers;

use App\Frame;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class FrameController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'required',
        'border' => 'required|dimensions:min_width=50,min_height=50',
        'border_mobile' => 'required|dimensions:min_width=50,min_height=50',
        'thumbnail' => 'required|dimensions:min_width=10,min_height=10'
    ];

    /**
     * The columns that contain image paths.
     *
     * @var array
     */
    protected $imageFields = [
        'border',
        'border_mobile',
        'thumbnail'
    ];

    /**
     * The images folder.
     *
     * @var array
     */
    protected $imagesFolder = 'frames';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth')->except(['index', 'show']);
        $this->middleware('api.admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Frame $frame)
    {
        return new CollectionResource($frame->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->getValidationRules('store'));

        $data = $this->saveImages($request, $data);

        $frame = Frame::create($data);

        return new ItemResource($frame);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Frame  $frame
     * @return \Illuminate\Http\Response
     */
    public function show(Frame $frame)
    {
        return new ItemResource($frame);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Frame  $frame
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Frame $frame)
    {
        $data = $request->validate($this->getValidationRules('update'));

        $data = $this->saveImages($request, $data);

        return new ItemResource(tap($frame)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Frame  $frame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Frame $frame)
    {
        $frame->delete();
        return ['success' => true];
    }

    /**
     * Return the images folder path according to the field name.
     *
     * @param  string  $field
     * @return string
     */
    protected function getImageFolder($field)
    {
        switch ($field) {
            case 'border':
                $folder = 'borders/desktop/';                break;

            case 'border_mobile':
                $folder = 'borders/mobile/';
                break;

            case 'thumbnail':
                $folder = 'thumbnails/';
                break;
        }
        return $this->imagesFolder . '/' . $folder;
    }
}
