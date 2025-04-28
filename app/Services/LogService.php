<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

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
    
    public function exception(Throwable $exception, string $message = null, array $context = []): void
    {
        $exceptionContext = [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];
        
        $message = $message ?? 'Exception occurred: ' . $exception->getMessage();
        
        $this->error($message, array_merge($exceptionContext, $context));
    }
    
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
        ];
        
        Log::$level($message, array_merge($defaultContext, $context));
    }
}