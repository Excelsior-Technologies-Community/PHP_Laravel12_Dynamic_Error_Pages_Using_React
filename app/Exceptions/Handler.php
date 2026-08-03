<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\ErrorVisit;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
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
        if ($this->isHttpException($exception)) {

            $statusCode = $exception->getStatusCode();

            /*
        |--------------------------------------------------------------------------
        | Store Error Visit
        |--------------------------------------------------------------------------
        */

            ErrorVisit::create([

                'status_code' => $statusCode,

                'url' => $request->fullUrl(),

                'ip_address' => $request->ip(),

                'user_agent' => substr((string) $request->userAgent(), 0, 255),

                'visited_at' => now(),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Load Error View
        |--------------------------------------------------------------------------
        */

            if (view()->exists("errors.{$statusCode}")) {

                return response()->view(

                    "errors.{$statusCode}",

                    [],

                    $statusCode

                );
            }

            return response()->view(

                'errors.generic',

                [

                    'exception' => $exception

                ],

                $statusCode

            );
        }

        return parent::render($request, $exception);
    }
}
