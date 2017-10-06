<?php

namespace App\Http\Middleware;

use App\Exceptions\TokenExpiredException;
use App\Exceptions\TokenInvalidException;
use App\Exceptions\TokenNotProvidedException;
use Closure;
use Tymon\JWTAuth\Middleware\GetUserFromToken;

class ValidateToken extends GetUserFromToken
{
    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        if (!$response) {
            switch ($error) {
                case 'token_not_provided':
                    throw new TokenNotProvidedException;
                    break;
                case 'token_expired':
                    throw new TokenExpiredException;
                    break;
                default:
                    throw new TokenInvalidException;
                    break;
            }
        }

        return $response;
    }
}
