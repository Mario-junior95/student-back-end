<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        parent::report($exception);
    }

    /**
     *200	Ok	The request was successfully completed.
     *201	Created	A new reesource was successfully created.
     *400	Bad Request	The request was invalid.
     *401	Unauthorized Invalid login credentials.
     *403	Forbidden You do not have enough permissions to perform this action.
     *404	Not Found The requested resource/page not found.
     *405	Method Not Allowed	This request is not supported by the resource.
     *409	Conflict The request could not be completed due to a conflict.
     *500	Internal Server Error The request was not completed due to an internal error on the server side.
     *503	Service Unavailable	The server was unavailable.
     */

    /**
     * Convert an authentication exception into an unauthenticated response.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated', 401);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            /**
             * $modelName = $exception->getModel();
             * $modelName will return for example like App\\Models\\User
             * but i want to return it to the user like below user 
             * so it should be like that 
             */
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exists any {$modelName} with the specified indentificator", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specific URL cannot be found", 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specific URL cannot be found", 404);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse('Cannot remove this resource permanently.It is related to any other resource', 409);
            }
        }

        /**
         * APP_DEBUG=true
         */
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        /**
         * APP_DEBUG=false
         */

        return $this->errorResponse('Unexpected Exception. Try later', 500);
    }

    /**
     * I get convertValidationExceptionToResponse function on i right click on render inside function render
     * This function will redirect me to another function where their is also convertValidationExceptionToResponse 
     * right click again on it and the function taht appear copy and past it below
     */

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);
    }
}
