<?php

namespace App\Http\Controllers;

use App\Format;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormatController extends Controller
{
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
    public function index(Format $format)
    {
        return new CollectionResource($format->paginate(10));
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
            'name' => 'required|max:50',
            'description' => 'required',
            'size' => ['required', Rule::in(['small', 'medium', 'large', 'xl'])],
            'type' => ['required', Rule::in(['FRAME', 'DISC', 'CUTOFF'])],
            'fixed' => 'required|boolean',
            'enabled' => 'required|boolean',
            'price' => 'required|integer',
            'cost' => 'required|integer',
            'frame_price' => 'required|integer',
            'frame_cost' => 'required|integer',
            'glass_price' => 'required|integer',
            'glass_cost' => 'required|integer',
            'pack_price' => 'required|integer',
            'pack_cost' => 'required|integer',
            'side' => 'required|integer',
            'minimum_pixels' => 'required|integer'
        ]);

        $format = Format::create($data);

        return new ItemResource($format);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Format  $format
     * @return \Illuminate\Http\Response
     */
    public function show(Format $format)
    {
        return new ItemResource($format);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Format  $format
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Format $format)
    {
        $data = $request->validate([
            'name' => 'sometimes|max:50',
            'description' => 'sometimes',
            'size' => ['sometimes', Rule::in(['small', 'medium', 'large', 'xl'])],
            'type' => ['sometimes', Rule::in(['FRAME', 'DISC', 'CUTOFF'])],
            'fixed' => 'sometimes|boolean',
            'enabled' => 'sometimes|boolean',
            'price' => 'sometimes|integer',
            'cost' => 'sometimes|integer',
            'frame_price' => 'sometimes|integer',
            'frame_cost' => 'sometimes|integer',
            'glass_price' => 'sometimes|integer',
            'glass_cost' => 'sometimes|integer',
            'pack_price' => 'sometimes|integer',
            'pack_cost' => 'sometimes|integer',
            'side' => 'sometimes|integer',
            'minimum_pixels' => 'sometimes|integer'
        ]);

        return new ItemResource(tap($format)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Format  $format
     * @return \Illuminate\Http\Response
     */
    public function destroy(Format $format)
    {
        $format->delete();
        return ['success' => true];
    }
}
