<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\TokenExpiredException;
use App\Exceptions\TokenInvalidException;
use App\Exceptions\TokenNotProvidedException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class OptionalAuthentication extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $token = $this->auth->setRequest($request)->getToken();

        if (!$token) {
            return $next($request);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            throw new TokenExpiredException;
        } catch (JWTException $e) {
            throw new TokenInvalidException;
        }

        if (!$user) {
            throw new TokenInvalidException;
        }

        return $next($request);
    }



    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    /*
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        dd('test');
        dd($response);

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
    */
}
