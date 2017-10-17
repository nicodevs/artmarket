<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:255',
        'thumbnail' => 'sometimes|required|dimensions:min_width=100,min_height=100',
        'cover' => 'sometimes|required|dimensions:min_width=100,min_height=100'
    ];

    /**
     * The columns that contain image paths.
     *
     * @var array
     */
    protected $imageFields = [
        'thumbnail',
        'cover'
    ];

    /**
     * The images folder.
     *
     * @var array
     */
    protected $imagesFolder = 'categories';

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
    public function index(Category $category)
    {
        return new CollectionResource($category->paginate(10));
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

        $category = Category::create($data);

        return new ItemResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new ItemResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate($this->getValidationRules('update'));

        $data = $this->saveImages($request, $data);

        return new ItemResource(tap($category)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return ['success' => true];
    }
}
