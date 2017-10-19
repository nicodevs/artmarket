<?php

namespace App\Http\Resources;

class Image extends Core\Item
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $image = parent::toArray($request);
        $image['categories'] = $this->categories->map(function ($category) {
            return ['id' => $category->id, 'name' => $category->name];
        });
        $image['comments'] = $this->comments()->with('user:id,username,first_name,last_name,avatar')->get();
        return $image;
    }
}
