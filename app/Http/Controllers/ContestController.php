<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class ContestController extends Controller
{

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'title' => 'required|max:255',
        'description' => 'required',
        'terms' => 'required',
        'cover' => 'required|dimensions:min_width=100,min_height=100',
        'prize_image_desktop' => 'sometimes|dimensions:min_width=100,min_height=100',
        'prize_image_mobile' => 'sometimes|dimensions:min_width=100,min_height=100',
        'winners_image_desktop' => 'sometimes|dimensions:min_width=100,min_height=100',
        'winners_image_mobile' => 'sometimes|dimensions:min_width=100,min_height=100',
        'expires_at' => 'required|date'
    ];

    /**
     * The columns that contain image paths.
     *
     * @var array
     */
    protected $imageFields = [
        'cover',
        'prize_image_desktop',
        'prize_image_mobile',
        'winners_image_desktop',
        'winners_image_mobile'
    ];

    /**
     * The images folder.
     *
     * @var array
     */
    protected $imagesFolder = 'contests';

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
    public function index(Contest $contest)
    {
        return new CollectionResource($contest->paginate(10));
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

        $contest = Contest::create($data);

        return new ItemResource($contest);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest)
    {
        return new ItemResource($contest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest)
    {
        $data = $request->validate($this->getValidationRules('update'));

        $data = $this->saveImages($request, $data);

        return new ItemResource(tap($contest)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest)
    {
        $contest->delete();
        return ['success' => true];
    }
}
