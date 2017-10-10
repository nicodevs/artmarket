<?php

namespace App\Http\Controllers;

use App\Shipping;
use Illuminate\Http\Request;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;

class ShippingController extends Controller
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
    public function index(Shipping $shipping)
    {
        return new CollectionResource($shipping->paginate(10));
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
            'price' => 'required|integer|min:1',
            'pickup' => 'required|boolean',
            'enabled' => 'required|boolean'
        ]);

        $shipping = Shipping::create($data);

        return new ItemResource($shipping);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function show(Shipping $shipping)
    {
        return new ItemResource($shipping);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        $data = $request->validate([
            'name' => 'sometimes|max:50',
            'description' => 'sometimes',
            'price' => 'sometimes|integer|min:1',
            'pickup' => 'sometimes|boolean',
            'enabled' => 'sometimes|boolean'
        ]);

        return new ItemResource(tap($shipping)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return ['success' => true];
    }
}
