<?php

namespace App\Http\Controllers;

use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth');
        $this->middleware('api.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Search $search
     * @return \Illuminate\Http\Response
     */
    public function index(Search $search)
    {
        return new CollectionResource($search->paginate(10));
    }
}
