<?php

namespace App\Exceptions;

use Exception;

class InvalidUploadedFileException extends Exception
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
            'message' => 'Invalid uploaded file',
            'code' => 'invalid_uploaded_file',
            'success' => false
        ], 422);
    }
}
