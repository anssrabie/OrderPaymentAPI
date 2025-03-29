<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function(){

            // Api Routes
            Route::middleware(['api','api_key'])->prefix('api/v1/auth')->group(base_path('routes/api/v1/auth.php'));
            Route::middleware(['api','api_key','auth:api'])->prefix('api/v1')->group(base_path('routes/api/v1/user.php'));
        },
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api_key' => ApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        function formatErrorApi($exception, $statusCode) {
            return response()->json([
                'message' => $statusCode == 404 ? 'Not Found' : $exception->getMessage(),
                'status' => false,
                'code' => $statusCode,
                'data' => [],
            ], $statusCode);
        }


        $exceptions->render(function (AuthorizationException $exception) {
            return formatErrorApi($exception, 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $exception) {
            return formatErrorApi($exception, 403);
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return formatErrorApi($exception, 401);
        });
//
//        //Handling any other exceptions
        $exceptions->render(function (Exception $exception) {
            return formatErrorApi($exception, 500);
        });
    })->create();
