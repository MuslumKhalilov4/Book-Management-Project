<?php

namespace App\Exceptions;

use App\Helpers\Helper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $status_code = 500;
        $error_message = 'Internal server error';

        switch (true) {
            case ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException):
                $status_code = 404;
                $error_message = 'Resource not found';
                break;

            case $exception instanceof AuthenticationException:
                $status_code = 401;
                $error_message = 'Unauthenticated';
                break;

            case $exception instanceof ValidationException:
                return response()->json([
                    'success' => 'false',
                    'message' => 'Validation failed',
                    'errors' => $exception->errors()
                ], 422);

            case $exception instanceof BaseException:
                $status_code = $exception->getStatusCode();
                $error_message = $exception->getMessage();
                break;

            default:
                $status_code = 500;
                $error_message = 'Internal server error';
                break;
        }

        return Helper::failResponse($error_message, $status_code);
    }
}
