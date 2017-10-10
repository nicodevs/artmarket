<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class ContestController extends Controller
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
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'terms' => 'required',
            'cover' => 'sometimes',
            'prize_image_desktop' => 'sometimes',
            'prize_image_mobile' => 'sometimes',
            'winners_image_desktop' => 'sometimes',
            'winners_image_mobile' => 'sometimes',
            'expires_at' => 'required|date'
        ]);

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
        $data = $request->validate([
            'title' => 'sometimes|max:255',
            'description' => 'sometimes',
            'terms' => 'sometimes',
            'cover' => 'sometimes',
            'prize_image_desktop' => 'sometimes',
            'prize_image_mobile' => 'sometimes',
            'winners_image_desktop' => 'sometimes',
            'winners_image_mobile' => 'sometimes',
            'expires_at' => 'sometimes|date'
        ]);

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
