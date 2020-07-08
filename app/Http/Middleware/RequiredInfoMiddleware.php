<?php

namespace App\Http\Middleware;

use Closure;

class RequiredInfoMiddleware
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
        $response = $next($request);

        if (\Auth::user()->timezone == 'UTC' || \Auth::user()->country_id === null) {
            return redirect('/required-info');
        }

        return $response;
    }
}
