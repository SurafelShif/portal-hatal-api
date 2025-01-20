<?php

use App\Enums\ResponseMessages;
use App\Http\Middleware\VerifyCookie;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend([
            VerifyCookie::class
        ]);
        $middleware->trimStrings(except: [
            fn(Request $request) => $request->path() === "api/general",
        ]);
        $middleware->convertEmptyStringsToNull(except: [
            fn(Request $request) => $request->path() === "api/general",
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return response()->json([
                'message' => ResponseMessages::FORBIDDEN,
                'exception' => class_basename($e),
            ], Response::HTTP_FORBIDDEN);
        });
    })
    ->create();
