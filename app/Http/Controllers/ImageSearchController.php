<?php

namespace App\Http\Controllers;

use App\Http\Resources\Image as ItemResource;
use App\Http\Resources\Images as CollectionResource;
use App\Image;
use Illuminate\Http\Request;

class ImageSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Image $image)
    {
        $data = $request->validate([
            'keyword' => 'required|min:3'
        ]);

        $search = $image->where('name', 'LIKE', "%{$this->keyword}%")
            ->orWhere('description', 'LIKE', "%{$this->keyword}%")
            ->orWhere('tags', 'LIKE', "%{$this->keyword}%")
            ->get();

        return new CollectionResource($search->paginate(10));
    }
}
