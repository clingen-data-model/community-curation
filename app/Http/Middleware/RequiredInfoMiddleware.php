<?php

namespace App\Http\Middleware;

use Closure;

class RequiredInfoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! session()->get('app_impersonate_required_info_bypass') && ! \Auth::user()->hasRequiredInfo()) {
            return redirect('/required-info');
        }

        return $response;
    }
}
