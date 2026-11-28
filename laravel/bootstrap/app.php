<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\ApiResponse;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\SwaggerProductionMiddleware;
use App\Http\Response\ResponseContext;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            ApiResponse::class,
        ]);

        $middleware->alias([
            'swagger.production' => SwaggerProductionMiddleware::class,
        ]);

        $middleware->alias([
            CheckRole::ROLE => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if (app()->environment('production')) {
            return response()->json([
                ResponseContext::SUCCESS => false,
                ResponseContext::MESSAGE => 'Internal server error',
            ], Response::HTTP_NOT_FOUND);
        }

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() === Response::HTTP_FORBIDDEN && $request->is('api/*')) {
                return response()->json([
                    'message' => 'You do not have sufficient permissions to perform this operation.',
                ], Response::HTTP_FORBIDDEN);
            }

            return null;
        });

        $exceptions->render(function (ApiException $e) {
            return response()->json([
                ResponseContext::SUCCESS => false,
                ResponseContext::MESSAGE => $e->getMessage(),
                'errors' => $e->errors,
            ], $e->getCode());
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                ResponseContext::SUCCESS => false,
                ResponseContext::MESSAGE => $e->getMessage(),
            ]);
        });

        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                ResponseContext::SUCCESS => false,
                ResponseContext::MESSAGE => 'Validation error',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (Throwable $e) {
            return response()->json([
                ResponseContext::SUCCESS => false,
                ResponseContext::MESSAGE => $e->getMessage(),
                'previous' => $e->getPrevious(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
