<?php

namespace App\Http\Controllers;

use App\Combo;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class ComboController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'required',
        'thumbnail' => 'required|dimensions:min_width=10,min_height=10',
        'thumbnail_mobile' => 'required|dimensions:min_width=10,min_height=10',
        'cart' => 'required'
    ];

    /**
     * The columns that contain image paths.
     *
     * @var array
     */
    protected $imageFields = [
        'thumbnail',
        'thumbnail_mobile'
    ];

    /**
     * The images folder.
     *
     * @var array
     */
    protected $imagesFolder = 'combos';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth')->except(['show']);
        $this->middleware('api.admin')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Combo $combo
     * @return \Illuminate\Http\Response
     */
    public function index(Combo $combo)
    {
        return new CollectionResource($combo->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->getValidationRules('store'));

        $data = $this->saveImages($request, $data);

        $combo = Combo::create($data);

        return new ItemResource($combo);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Combo $combo
     * @return \Illuminate\Http\Response
     */
    public function show(Combo $combo)
    {
        return new ItemResource($combo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Combo $combo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Combo $combo)
    {
        $data = $request->validate($this->getValidationRules('update'));

        $data = $this->saveImages($request, $data);

        return new ItemResource(tap($combo)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Combo  $combo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Combo $combo)
    {
        $combo->delete();
        return ['success' => true];
    }
}
