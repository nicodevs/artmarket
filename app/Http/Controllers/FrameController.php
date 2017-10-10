<?php

namespace App\Http\Controllers;

use App\Frame;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class FrameController extends Controller
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
        $data = $request->validate([
            'name' => 'required|max:50',
            'description' => 'required',
            'border' => 'required',
            'border_mobile' => 'required',
            'thumbnail' => 'required'
        ]);

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
        $data = $request->validate([
            'name' => 'sometimes|max:50',
            'description' => 'sometimes',
            'border' => 'sometimes',
            'border_mobile' => 'sometimes',
            'thumbnail' => 'sometimes'
        ]);

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
}
