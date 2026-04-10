<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogOauthTokenRequests
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

        if ($request->is('oauth/token') && $response->getStatusCode() >= 400) {
            Log::error('Temporary oauth/token debug', [
                'method' => $request->method(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
                'origin' => $request->headers->get('origin'),
                'content_type' => $request->headers->get('content-type'),
                'accept' => $request->headers->get('accept'),
                'grant_type' => $request->input('grant_type'),
                'has_client_id' => $request->filled('client_id'),
                'has_client_secret' => $request->filled('client_secret'),
                'has_username' => $request->filled('username'),
                'has_password' => $request->filled('password'),
                'has_refresh_token' => $request->filled('refresh_token'),
                'has_code' => $request->filled('code'),
                'has_code_verifier' => $request->filled('code_verifier'),
                'redirect_uri' => $request->input('redirect_uri'),
                'body_keys' => array_keys($request->all()),
                'query_keys' => array_keys($request->query()),
                'status_code' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}