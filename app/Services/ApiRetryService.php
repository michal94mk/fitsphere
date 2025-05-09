<?php

namespace App\Services;

use Closure;
use Throwable;
use App\Exceptions\ApiException;
use App\Exceptions\RateLimitException;
use Illuminate\Support\Facades\Log;

// Handles API calls with automatic retry and exponential backoff
class ApiRetryService
{
    // Execute API call with automatic retry for transient failures
    public function executeWithRetry(
        Closure $callback,
        string $serviceName,
        string $endpoint,
        int $maxAttempts = 3,
        array $retryStatusCodes = [408, 429, 500, 502, 503, 504],
        int $initialDelay = 500,
        float $backoffFactor = 2.0
    ) {
        $attempts = 0;
        $lastException = null;
        $delay = $initialDelay;
        
        do {
            $attempts++;
            try {
                $result = $callback();
                
                // Log success after a previous failure
                if ($attempts > 1) {
                    Log::info("API {$serviceName} request succeeded after {$attempts} attempts", [
                        'endpoint' => $endpoint,
                        'service' => $serviceName
                    ]);
                }
                
                return $result;
            } catch (Throwable $exception) {
                $lastException = $exception;
                
                $statusCode = $this->getStatusCodeFromException($exception);
                $shouldRetry = in_array($statusCode, $retryStatusCodes);
                
                // Handle rate limiting with Retry-After header
                $retryAfter = null;
                
                if ($statusCode === 429) {
                    // Try to get Retry-After header if available
                    if (method_exists($exception, 'response') && $exception->response) {
                        $retryAfter = $exception->response->header('Retry-After');
                    }
                    
                    // If we have a specific Retry-After value, use it
                    if ($retryAfter) {
                        $delay = $retryAfter * 1000; // convert to ms
                        Log::info("Rate limit hit for {$serviceName}, retry after {$retryAfter}s", [
                            'endpoint' => $endpoint
                        ]);
                        
                        // If this is the last attempt, throw specialized exception
                        if ($attempts >= $maxAttempts) {
                            throw new RateLimitException(
                                $serviceName,
                                $retryAfter,
                                'request',
                                "Przekroczono limit zapytań do {$serviceName}, następna próba możliwa za {$retryAfter}s"
                            );
                        }
                    }
                }
                
                if ($shouldRetry && $attempts < $maxAttempts) {
                    Log::warning("Retrying API {$serviceName} request ({$attempts}/{$maxAttempts}) after error", [
                        'endpoint' => $endpoint,
                        'error' => $exception->getMessage(),
                        'delay_ms' => $delay,
                        'status_code' => $statusCode
                    ]);
                    
                    usleep($delay * 1000); // usleep uses microseconds, so multiply by 1000
                    $delay = intval($delay * $backoffFactor); // increase delay for each retry
                } else {
                    break; // Don't retry if max attempts reached or status code doesn't qualify
                }
            }
        } while ($attempts < $maxAttempts);
        
        // All attempts failed - throw ApiException
        if ($lastException instanceof ApiException) {
            throw $lastException;
        }
        
        // Convert generic exception to ApiException
        throw new ApiException(
            $endpoint,
            "Failed after {$attempts} attempts: " . $lastException->getMessage(),
            $serviceName,
            $this->getStatusCodeFromException($lastException),
            $lastException
        );
    }
    
    // Extract HTTP status code from exception
    protected function getStatusCodeFromException(Throwable $e): ?int
    {
        // Check if it's our ApiException which already has a status code
        if ($e instanceof ApiException) {
            return $e->getStatusCode();
        }
        
        // Check for standard HTTP/Guzzle exceptions
        if (method_exists($e, 'getCode')) {
            $code = $e->getCode();
            if (is_int($code) && $code >= 100 && $code < 600) {
                return $code;
            }
        }
        
        // Check if exception has a response with status code
        if (method_exists($e, 'response') && $e->response && method_exists($e->response, 'status')) {
            return $e->response->status();
        }
        
        return null;
    }
} 