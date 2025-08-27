<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // Handle API requests with JSON responses
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Exception $exception)
    {
        // Handle our custom exceptions
        if ($exception instanceof BaseException) {
            return ApiResponse::error(
                $exception->getMessage(),
                $exception->getStatusCode(),
                ['error_code' => $exception->getErrorCode()]
            );
        }

        // Handle validation exceptions
        if ($exception instanceof ValidationException) {
            return ApiResponse::validationError(
                $exception->errors(),
                'Validation failed'
            );
        }

        // Handle model not found exceptions
        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::notFound('Resource not found');
        }

        // Handle 404 exceptions
        if ($exception instanceof NotFoundHttpException) {
            return ApiResponse::notFound('Endpoint not found');
        }

        // Handle unauthorized exceptions
        if ($exception instanceof UnauthorizedHttpException) {
            return ApiResponse::unauthorized($exception->getMessage());
        }

        // Handle access denied exceptions
        if ($exception instanceof AccessDeniedHttpException) {
            return ApiResponse::forbidden($exception->getMessage());
        }

        // Handle generic exceptions
        $message = app()->environment('production') 
            ? 'An error occurred while processing your request'
            : $exception->getMessage();

        return ApiResponse::error($message, 500);
    }
}
