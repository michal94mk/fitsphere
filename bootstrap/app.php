<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\ApiException;
use App\Exceptions\EmailSendingException;
use App\Exceptions\RateLimitException;
use Illuminate\Validation\ValidationException;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
        // Register the SetLocale middleware to run on all web requests
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class
        ]);
        
        // Register RequestTracking middleware globally for web and API requests
        $middleware->append(\App\Http\Middleware\RequestTracking::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Register custom exception renderers for API requests
        $exceptions->renderable(function (ApiException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'service' => $e->getServiceName(),
                    'status_code' => $e->getStatusCode() ?: 500
                ], $e->getStatusCode() ?: 500);
            }
        });

        $exceptions->renderable(function (EmailSendingException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                ], 500);
            }
        });
        
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => 'Invalid input data',
                    'errors' => $e->errors(),
                ], 422);
            }
        });
        
        $exceptions->renderable(function (RateLimitException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $headers = [];
                if ($e->getRetryAfter()) {
                    $headers['Retry-After'] = $e->getRetryAfter();
                }
                
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'service' => $e->getServiceName(),
                    'retry_after' => $e->getRetryAfter(),
                ], 429, $headers);
            }
        });
    })->create();