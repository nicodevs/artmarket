<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
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
            'message' => 'Invalid credentials',
            'errors' => [
                'password' => ['Wrong email or password']
            ],
            'code' => 'invalid_credentials',
            'success' => false
        ], 401);
    }
}
