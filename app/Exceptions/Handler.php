<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Exceptions\TokenExpiredExepction;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof UnauthorizedHttpException) {
            if ($exception->getPrevious() instanceof TokenExpiredExepction) {
                return response([
                    'error' => 'Token Expired'
                ], Response::HTTP_NOT_FOUND);
            } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                return response([
                    'error' => 'Token Invalid'
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response([
                        'error' => $exception->getMessage()
                    ] , $exception->getCode() ? $exception->getCode() : 400
                );
            }
        }
    }
}
