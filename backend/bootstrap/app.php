<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
                'errors' => [],
            ], 401);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation errors',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'code' => 404,
                'message' => 'Element not found',
                'errors' => [],
            ], 404);
        });

        $exceptions->render(function (\Exception $e, Request $request) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
                'errors' => [],
                // 'class' => get_class($e),
            ], 500);
        });
    })->create();
