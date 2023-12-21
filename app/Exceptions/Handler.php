<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use Response;
use Error;
use DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }
    
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'msg' => 'Unauthenticated.'], 403);
        }

        return redirect()->guest(route('login'));
    }    

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            if ($exception->getStatusCode() == 419) {
                return redirect('/nova?t='.time());
            }
        }
        if (in_array($request->method(), ['POST', 'PATCH', 'DELETE'])) {
            DB::rollBack();
        }
        if ($exception instanceof Error) {
            return \Response::json([
                'success' => false,
                'msg' => $exception->getMessage(),
                'code' => $exception->getCode()
            ], 500);
        }
        if ($exception instanceof ApiException){
            return Response::json([
                'success' => false,
                'msg' => $exception->getMessage(),
                'code' => $exception->getCode()
            ], 200);
        }
        return parent::render($request, $exception);
    }
}
