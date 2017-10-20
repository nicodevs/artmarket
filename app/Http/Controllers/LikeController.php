<?php

namespace App\Http\Controllers;

use App\Like;
use App\Events\ImageLiked;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Image;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Image $image)
    {
        $like = auth()->user()->likesImage($image->id);

        event(new ImageLiked($image, $like));

        return new ItemResource($like);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Format  $format
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $result = auth()->user()->dislikesImage($image->id);
        return ['success' => true];
    }
}
