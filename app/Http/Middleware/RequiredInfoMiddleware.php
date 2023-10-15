<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class RequiredInfoMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! session()->get('app_impersonate_required_info_bypass') && ! \Auth::user()->hasRequiredInfo()) {
            return redirect('/required-info');
        }

        return $response;
    }
}
