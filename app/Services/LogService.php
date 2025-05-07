<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;
use App\Exceptions\ApiException;
use App\Exceptions\EmailSendingException;

class LogService
{
    public function error(string $message, array $context = []): void
    {
        $this->logWithContext('error', $message, $context);
    }
    
    public function warning(string $message, array $context = []): void
    {
        $this->logWithContext('warning', $message, $context);
    }
    
    public function info(string $message, array $context = []): void
    {
        $this->logWithContext('info', $message, $context);
    }
    
    /**
     * Enhanced exception logging with specialized handling for custom exceptions
     * 
     * @param Throwable $exception The exception to log
     * @param string|null $message Custom message to prefix the exception details
     * @param array $context Additional context to include in the log
     */
    public function exception(Throwable $exception, string $message = null, array $context = []): void
    {
        $exceptionClass = get_class($exception);
        $exceptionContext = [
            'class' => $exceptionClass,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
        
        // Add specialized context based on the exception type
        if ($exception instanceof ApiException) {
            $exceptionContext['service'] = $exception->getServiceName();
            $exceptionContext['endpoint'] = $exception->getEndpoint();
            $exceptionContext['status_code'] = $exception->getStatusCode();
            $message = $message ?? 'API Error: ' . $exception->getMessage();
        } elseif ($exception instanceof EmailSendingException) {
            $exceptionContext['recipient'] = $exception->getRecipient();
            $exceptionContext['mailable'] = $exception->getMailableClass();
            $message = $message ?? 'Email Sending Error: ' . $exception->getMessage();
        } else {
            // For all other exceptions, include trace
            $exceptionContext['trace'] = $exception->getTraceAsString();
            $message = $message ?? 'Exception occurred: ' . $exception->getMessage();
        }
        
        // Determine log level based on exception severity
        $level = 'error';
        
        // HTTP 5xx errors and PHP Errors are critical
        if ($exception->getCode() >= 500 || $exception instanceof \Error) {
            $level = 'critical';
            // Always include trace for critical errors
            $exceptionContext['trace'] = $exception->getTraceAsString();
        } 
        // HTTP 4xx errors and most custom exceptions are warnings
        elseif ($exception->getCode() >= 400 && $exception->getCode() < 500) {
            $level = 'warning';
        }
        
        $this->$level($message, array_merge($exceptionContext, $context));
    }
    
    /**
     * Log critical errors that require immediate attention
     */
    public function critical(string $message, array $context = []): void
    {
        $this->logWithContext('critical', $message, $context);
    }
    
    /**
     * Logs with additional contextual information about the request and user
     */
    protected function logWithContext(string $level, string $message, array $context = []): void
    {
        $request = request();
        $user = $request->user();
        
        $defaultContext = [
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'session_id' => session()->getId(),
        ];
        
        Log::$level($message, array_merge($defaultContext, $context));
    }
}