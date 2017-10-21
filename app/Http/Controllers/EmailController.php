<?php

namespace App\Http\Controllers;

use App\Email;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmailController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Email $email)
    {
        return new CollectionResource($email->paginate(10));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        return new ItemResource($email);
    }
}
