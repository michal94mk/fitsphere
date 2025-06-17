<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Services\LogService;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                $this->logAdditionalContext($e);
            }
        });
        
        // Handle EmailSendingException specifically
        $this->reportable(function (EmailSendingException $e) {
            // Use LogService instead of direct Log
            app(LogService::class)->exception($e, 'Email failed to send', [
                'recipient' => $e->getRecipient(),
                'mailable' => $e->getMailableClass(),
            ]);
            
            return false; // Prevent default reporting
        });
        
        // Handle ApiException specifically
        $this->reportable(function (ApiException $e) {
            // Use LogService instead of direct Log
            app(LogService::class)->exception($e, 'API call failed', [
                'service' => $e->getServiceName(),
                'endpoint' => $e->getEndpoint(),
                'status_code' => $e->getStatusCode(),
            ]);
            
            // For Spoonacular API, log additional information
            if ($e->getServiceName() === 'Spoonacular') {
                Log::info('Spoonacular API rate limits may apply', [
                    'api_key_configured' => !empty(config('services.spoonacular.key')),
                ]);
            }
            
            return false; // Prevent default reporting
        });
        
        // Handle RateLimitException
        $this->reportable(function (RateLimitException $e) {
            app(LogService::class)->warning('Rate limit exceeded', [
                'service' => $e->getServiceName(),
                'retry_after' => $e->getRetryAfter(),
                'limit_type' => $e->getLimitType(),
            ]);
            
            return false; // Prevent default reporting
        });

        // Add specialized rendering for API exceptions when in API context
        $this->renderable(function (ApiException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                // Use translated message for user-facing API responses
                $userMessage = $e->getServiceName() === 'Spoonacular' 
                    ? __('exceptions.spoonacular_api_failed')
                    : __('exceptions.api_call_failed');
                
                return response()->json([
                    'error' => true,
                    'message' => $userMessage,
                    'service' => $e->getServiceName(),
                    'status_code' => $e->getStatusCode() ?: 500
                ], $e->getStatusCode() ?: 500);
            }
        });

        // Render EmailSendingException as JSON if in API context
        $this->renderable(function (EmailSendingException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.email_sending_failed'),
                ], 500);
            }
        });
        
        // Dodanie obsługi ValidationException - zwracanie ustrukturyzowanych błędów walidacji
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.validation_failed'),
                    'errors' => $e->errors(),
                ], 422);
            }
        });
        
        // Render RateLimitException with proper headers
        $this->renderable(function (RateLimitException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $headers = [];
                if ($e->getRetryAfter()) {
                    $headers['Retry-After'] = $e->getRetryAfter();
                }
                
                return response()->json([
                    'error' => true,
                    'message' => __('exceptions.rate_limit_exceeded', ['retry_after' => $e->getRetryAfter()]),
                    'service' => $e->getServiceName(),
                    'retry_after' => $e->getRetryAfter(),
                ], 429, $headers);
            }
        });
    }
    
    protected function logAdditionalContext(Throwable $exception): void
    {
        if (!$exception instanceof ValidationException && 
            !$exception instanceof AuthenticationException) {
            
            // Use LogService for enhanced logging
            app(LogService::class)->exception($exception);
        }
    }
} 