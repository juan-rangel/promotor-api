<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;

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
        // return response()->json($request->header('Content-Type'));
        // is this request asks for json?
        // return response()->json($exception->getMessage());
        if (Str::contains($request->header('Content-Type'), ['application/json', 'application/x-www-form-urlencoded'])) {

            /*  is this exception? */
            if (!empty($exception)) {

                // set default error message
                $response = [
                    'error' => $exception->getMessage()
                ];

                // If debug mode is enabled
                if (config('app.debug')) {
                    // Add the exception class name, message and stack trace to response
                    $response['exception'] = get_class($exception); // Reflection might be better here
                    $response['message'] = $exception->getMessage();
                    $response['trace'] = $exception->getTrace();
                }

                $status = 400;

                // get correct status code

                // is this validation exception
                if ($exception instanceof ValidationException) {
                    return $this->convertValidationExceptionToResponse($exception, $request);

                    // is it authentication exception
                } else if ($exception instanceof AuthenticationException) {
                    $status = 401;
                    $response['error'] = 'Can not finish authentication!';

                    //is it DB exception
                } else if ($exception instanceof \PDOException) {
                    $status = 500;
                    $response['error'] = 'Can not finish your query request!';

                    // is it http exception (this can give us status code)
                } else if ($this->isHttpException($exception)) {
                    $status = $exception->getStatusCode();
                    $response['error'] = 'Request error!';
                } else {

                    // for all others check do we have method getStatusCode and try to get it
                    // otherwise, set the status to 400
                    $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;
                }

                return response()->json($response, $status);
            }
        }

        return parent::render($request, $exception);
    }
}
