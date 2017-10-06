<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Resources\Json\Resource;

class Item extends Resource
{
    /**
     * Get any additional data that should be returned with the resource array.
     * Resource available as $this->resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true
        ];
    }
}
