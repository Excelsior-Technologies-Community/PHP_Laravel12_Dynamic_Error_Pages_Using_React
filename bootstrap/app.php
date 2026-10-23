<?php

use App\Http\Middleware\CheckBlockedIp;
use App\Models\ErrorVisit;
use App\Services\SecurityRadarService;
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
        $middleware->web(append: [
            CheckBlockedIp::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Throwable $exception, $request) {

            if ($exception instanceof HttpExceptionInterface) {

                $statusCode = $exception->getStatusCode();

                try {
                    $url = $request->fullUrl();
                    $ip = $request->ip();
                    $ua = substr((string) $request->userAgent(), 0, 255);

                    // Run Automated Security Radar Analysis
                    $analysis = SecurityRadarService::analyze($url, $ua, $ip);

                    ErrorVisit::create([
                        'status_code' => $statusCode,
                        'url' => $url,
                        'ip_address' => $ip,
                        'user_agent' => $ua,
                        'threat_level' => $analysis['threat_level'],
                        'threat_category' => $analysis['threat_category'],
                        'device_type' => $analysis['device_type'],
                        'browser' => $analysis['browser'],
                        'os' => $analysis['os'],
                        'country' => $analysis['country'],
                        'country_code' => $analysis['country_code'],
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
