<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\NotImplementedException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        NotImplementedException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotImplementedException) {
            return response('Not implemented', 501);
        }

        if ($exception instanceof AuthorizationException) {
            session()->flash('error', 'Not authorized.');
            return redirect('/');
        }
        if ($exception instanceof UnauthorizedException) {
            request()->session->flash('error', 'Not authorized.');
            return redirect('/');
        }

        return parent::render($request, $exception);
    }
}
