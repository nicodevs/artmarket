<?php

namespace App\Http\Resources;

class Images extends Core\Collection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
