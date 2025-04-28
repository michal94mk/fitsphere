<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

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
            Log::warning('Email failed to send', [
                'recipient' => $e->getRecipient(),
                'mailable' => $e->getMailableClass(),
                'message' => $e->getMessage()
            ]);
            
            return false; // Let the default reporting continue
        });
        
        // Handle ApiException specifically
        $this->reportable(function (ApiException $e) {
            Log::warning('API call failed', [
                'service' => $e->getServiceName(),
                'endpoint' => $e->getEndpoint(),
                'status_code' => $e->getStatusCode(),
                'message' => $e->getMessage()
            ]);
            
            // For Spoonacular API, log additional information
            if ($e->getServiceName() === 'Spoonacular') {
                Log::info('Spoonacular API rate limits may apply', [
                    'api_key_configured' => !empty(config('services.spoonacular.key')),
                ]);
            }
            
            return false; // Let the default reporting continue
        });
    }
    
    protected function logAdditionalContext(Throwable $exception): void
    {
        if (!$exception instanceof ValidationException && 
            !$exception instanceof AuthenticationException) {
            
            $request = request();
            $user = $request->user();
            
            $data = [
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'client_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];
            
            // For critical exceptions, add more details
            if ($exception->getCode() >= 500 || $exception instanceof \Error) {
                $data['trace'] = $exception->getTraceAsString();
                $data['line'] = $exception->getLine();
                $data['file'] = $exception->getFile();
            }
            
            Log::error('Exception details', $data);
        }
    }
} 