<?php

namespace App\Exceptions;

use Exception;

class UserUnauthorizedException extends Exception
{

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Your user has no access to this object',
            'code' => 'user_unauthorized',
            'user' => auth()->user()? auth()->user()->toArray() : false
        ], 401);
    }
}
