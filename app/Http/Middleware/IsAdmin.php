<?php

namespace App\Http\Middleware;

use App\Exceptions\UserUnauthorizedException;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user() && auth()->user()->admin) {
            return $next($request);
        }

        throw new UserUnauthorizedException;
    }
}
