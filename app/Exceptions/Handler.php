<?php

namespace App\Exceptions;

use core\domain\exception\AlreadyExistsException;
use core\domain\exception\EntityValidationException;
use core\domain\exception\NotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        if ($exception instanceof NotFoundException) {
            return $this->showError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AlreadyExistsException) {
            return $this->showError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof EntityValidationException) {
            return $this->showError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // if ($exception instanceof NotificationException) {
        //     return $this->showError($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        // } // ok => HTTP_INTERNAL_SERVER_ERROR

        return parent::render($request, $exception);
    }

    private function showError(string $message, int $statusCode)
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }    
}
