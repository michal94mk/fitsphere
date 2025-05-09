<?php

namespace App\Exceptions;

use Exception;

// Exception thrown when rate limits are hit on external services
class RateLimitException extends Exception
{
    protected $serviceName;
    protected $retryAfter;
    protected $limitType;
    
    public function __construct(
        string $serviceName,
        ?int $retryAfter = null,
        string $limitType = 'request',
        ?string $message = null,
        int $code = 429
    ) {
        $this->serviceName = $serviceName;
        $this->retryAfter = $retryAfter;
        $this->limitType = $limitType;
        
        $baseMessage = "Przekroczono limit {$limitType} dla usługi {$serviceName}";
        if ($retryAfter) {
            $baseMessage .= ". Spróbuj ponownie za {$retryAfter} sekund";
        }
        
        $finalMessage = $message ?: $baseMessage;
        
        parent::__construct($finalMessage, $code);
    }
    
    public function getServiceName(): string 
    {
        return $this->serviceName;
    }
    
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
    
    public function getLimitType(): string
    {
        return $this->limitType;
    }
} 