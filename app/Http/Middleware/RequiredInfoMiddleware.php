<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiredInfoMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->session()->get('app_impersonate_required_info_bypass') && ! $request->user()->hasRequiredInfo()) {
            return redirect()->to('/required-info');
        }

        return $response;
    }
}
