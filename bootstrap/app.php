<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware (replaces Kernel::$middleware)
        $middleware->append([
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Web group additions (core group items are auto-included in L11)
        $middleware->web(append: [
            \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
        ]);
        // Replace VerifyCsrfToken for L11 compatibility
        $middleware->web(replace: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // Middleware aliases (replaces Kernel::$routeMiddleware)
        $middleware->alias([
            'auth'               => \App\Http\Middleware\Authenticate::class,
            'guest'              => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'required-info'      => \App\Http\Middleware\RequiredInfoMiddleware::class,
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Middleware priority (replaces Kernel::$middlewarePriority)
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\Authenticate::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class,
            \App\Http\Middleware\RequiredInfoMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Replaces Handler::$dontReport
        $exceptions->dontReport([
            \App\Exceptions\NotImplementedException::class,
        ]);

        // Replaces Handler::render()
        $exceptions->render(function (\App\Exceptions\NotImplementedException $e) {
            return response('Not implemented', 501);
        });

        $exceptions->render(function (AuthorizationException|UnauthorizedException $e, $request) {
            if (!$request->expectsJson()) {
                session()->flash('error', 'Not authorized.');
                return redirect('/');
            }
        });
    })
    ->create();
