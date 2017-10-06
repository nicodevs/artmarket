<?php

namespace App\Exceptions;

use Exception;

class TokenNotProvidedException extends Exception
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
            'message' => 'Token not provided',
            'code' => 'token_not_provided',
            'success' => false
        ], 401);
    }
}
