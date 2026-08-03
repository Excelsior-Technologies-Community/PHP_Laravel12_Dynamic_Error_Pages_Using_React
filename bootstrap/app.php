<?php

use App\Models\ErrorVisit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Throwable $exception, $request) {

            if ($exception instanceof HttpExceptionInterface) {

                $statusCode = $exception->getStatusCode();

                try {

                    ErrorVisit::create([
                        'status_code' => $statusCode,
                        'url' => $request->fullUrl(),
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 255),
                        'visited_at' => now(),
                    ]);

                } catch (\Throwable $e) {
                    // Prevent logging failures from breaking the error page.
                }

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

            return null;
        });

    })

    ->create();