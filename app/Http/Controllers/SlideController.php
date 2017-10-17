<?php

namespace App\Http\Controllers;

use App\Slide;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class SlideController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'description' => 'required',
        'desktop' => 'required|dimensions:min_width=100,min_height=100',
        'mobile' => 'required|dimensions:min_width=100,min_height=100',
        'sequence' => 'integer',
        'href' => 'url'
    ];

    /**
     * The columns that contain image paths.
     *
     * @var array
     */
    protected $imageFields = [
        'desktop',
        'mobile'
    ];

    /**
     * The images folder.
     *
     * @var array
     */
    protected $imagesFolder = 'slides';

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
    public function index(Slide $slide)
    {
        return new CollectionResource($slide->paginate(10));
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

        $slide = Slide::create($data);

        return new ItemResource($slide);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function show(Slide $slide)
    {
        return new ItemResource($slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slide $slide)
    {
        $data = $request->validate($this->getValidationRules('update'));

        $data = $this->saveImages($request, $data);

        return new ItemResource(tap($slide)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        return ['success' => true];
    }
}
