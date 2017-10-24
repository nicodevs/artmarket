<?php

namespace App\Http\Controllers;

use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Image;
use App\Email;
use Illuminate\Http\Request;

class ImageFlagController extends Controller
{

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'message' => 'required'
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Email $email, Image $image)
    {
        $data = $request->validate($this->getValidationRules('store'));

        $email->composeImageFlagEmail($data, $image);

        return ['success' => true];
    }
}
