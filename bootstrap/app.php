<?php

use App\Enums\ErrorCode;
use App\Http\Middleware\HasAnyRoleMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\ValidPasswordMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'role.any' => HasAnyRoleMiddleware::class,
            'valid_password' => ValidPasswordMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

         $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::UNAUTHENTICATED,
                'message' => 'Unauthenticated',
            ], 401);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::NOT_FOUND,
                'message' => 'Not Found',
            ], 404);
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'error_code' => ErrorCode::VALIDATION_FAILED,
                'errors' => formatValidationErrors($e->errors()),
            ], 422);
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::RESOURCE_NOT_FOUND,
                'message' => 'Resource not found',
            ], 404);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::METHOD_NOT_ALLOWED,
                'message' => 'Invalid request method',
            ], 405);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::TOO_MANY_REQUESTS,
                'message' => 'Too many requests, slow down',
            ], 429);
        });


        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::FORBIDDEN,
                'message' => 'Forbidden',
            ], 403);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'error_code' => ErrorCode::FORBIDDEN,
                'message' => 'Forbidden',
            ], 403);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->json([
                    'success' => false,
                    'error_code' => ErrorCode::FORBIDDEN,
                    'message' => 'Forbidden',
                ], 403);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error_code' => ErrorCode::SERVER_ERROR,
                'error'   => app()->isLocal() ? $e->getMessage() : null,
                // 'trace'   => app()->isLocal() ? $e->getTrace() : null,
            ], 500);
        });


    })->create();
