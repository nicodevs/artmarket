<?php

namespace App\Exceptions;

use Exception;

class TokenExpiredException extends Exception
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
            'message' => 'Token expired',
            'code' => 'token_expired',
            'success' => false
        ], 401);
    }
}
