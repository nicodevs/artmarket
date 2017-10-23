<?php

namespace App\Exceptions;

use Exception;

class InvalidSummaryTypeException extends Exception
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
            'message' => 'Invalid summary type',
            'errors' => [
                'type' => ['Invalid summary type']
            ],
            'code' => 'invalid_summary_type',
            'success' => false
        ], 422);
    }
}
