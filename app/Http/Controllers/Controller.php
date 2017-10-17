<?php

namespace App\Http\Controllers;

use App\Exceptions\UserUnauthorizedException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Checks if the logged user has access to the requested model.
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected function checkUserAccess($model)
    {
        if (!auth()->user()->admin && !auth()->user()->owns($model)) {
            throw new UserUnauthorizedException;
        }
    }

    /**
     * Returns the validation rules.
     *
     * @return array
     */
    protected function getValidationRules($action)
    {
        $rules = array_merge($this->rules);
        if ($action === 'update') {
            foreach ($rules as $field => $rule) {
                if (!str_contains($rule, 'sometimes')) {
                    $rules[$field] = 'sometimes|' . $rules[$field];
                }
            }
        }
        return $rules;
    }

    /**
     * Saves the uploaded images and return their paths.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $data
     * @return array
     */
    protected function saveImages(Request $request, $data)
    {
        foreach ($this->imageFields as $field) {
            if (isset($data[$field])) {
                $filename = $filename = md5(uniqid()) . '.' . $request->file($field)->getClientOriginalExtension();
                $request->file($field)->storeAs($this->imagesFolder, $filename, 'uploads');
                $data[$field] = $filename;
            }
        }
        return $data;
    }
}
