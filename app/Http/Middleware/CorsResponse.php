<?php

namespace App\Http\Middleware;

use Closure;

class CorsResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', 'https://www.youtube.com');
        $response->header('Access-Control-Allow-Origin', 'https://youtu.be');

        return $response;
    }
}
