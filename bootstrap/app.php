<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\CheckCsrf;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('Modules/Admin/routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/custom.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => AdminAuth::class,
            'permission' => PermissionMiddleware::class,
            'Debugbar' => Debugbar::class,
        ]);
        $middleware->web(replace: [
            ValidateCsrfToken::class => CheckCsrf::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $exception, Request $request) {
            // Handle maintenance mode (503 Service Unavailable)
            if (app()->isDownForMaintenance()) {
                if (Str::startsWith($request->path(), app('backend.prefix'))) {
                    return response()->view('admin::errors.503', [], Response::HTTP_SERVICE_UNAVAILABLE);
                } else {
                    return response()->view('errors.503', [], Response::HTTP_SERVICE_UNAVAILABLE);
                }
            }

            // Skip custom rendering when in debug mode (show Laravel's debug screen)
            if (app()->hasDebugModeEnabled()) {
                return null;
            }

            // Handle 404 Not Found (route not found or error exception)
            if ($exception instanceof NotFoundHttpException || $exception instanceof ErrorException) {
                if (Str::startsWith($request->path(), app('backend.prefix'))) {
                    return response()->view('admin::errors.404', [], Response::HTTP_NOT_FOUND);
                } else {
                    return response()->view('errors.404', [], Response::HTTP_NOT_FOUND);
                }
            }

            // Handle 500 Internal Server Error
            if ($exception instanceof HttpException && $exception->getStatusCode() === 500) {
                if (Str::startsWith($request->path(), app('backend.prefix'))) {
                    return response()->view('admin::errors.500', [], Response::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                    return response()->view('errors.500', [], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            // Handle 405 Method Not Allowed (wrong HTTP verb like POST instead of GET)
            if ($exception instanceof MethodNotAllowedHttpException) {
                if (Str::startsWith($request->path(), app('backend.prefix'))) {
                    return response()->view('admin::errors.404', [], Response::HTTP_METHOD_NOT_ALLOWED);
                } else {
                    return response()->view('errors.404', [], Response::HTTP_METHOD_NOT_ALLOWED);
                }
            }

            // Handle 404 for missing database models or bad method calls
            if ($exception instanceof ModelNotFoundException || $exception instanceof BadMethodCallException) {
                if (Str::startsWith($request->path(), app('backend.prefix'))) {
                    return response()->view('admin::errors.404', [], Response::HTTP_NOT_FOUND);
                } else {
                    return response()->view('errors.404', [], Response::HTTP_NOT_FOUND);
                }
            }

            // Handle 403 Forbidden and 419 Page Expired
            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
                if ($statusCode === 419 || $statusCode === 403) {
                    if (Str::startsWith($request->path(), app('backend.prefix'))) {
                        return response()->view('admin::errors.404', [], Response::HTTP_NOT_FOUND);
                    } else {
                        return response()->view('errors.404', [], Response::HTTP_NOT_FOUND);
                    }
                }
            }
        });
    })->create();
