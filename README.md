
# Installation

Add JWT to providers:
https://github.com/tymondesigns/jwt-auth/wiki/Installation

Then run:

    php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
    php artisan jwt:generate

Also add the middleware in app/Http/Kernel.php, in the $routeMiddleware array:

    'api.auth' => \App\Http\Middleware\ValidateToken::class

