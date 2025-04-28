<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    protected $endpoint;
    protected $statusCode;
    protected $serviceName;
    
    public function __construct(
        string $endpoint, 
        string $message = "API request failed", 
        string $serviceName = "unknown", 
        ?int $statusCode = null, 
        ?Throwable $previous = null
    ) {
        $this->endpoint = $endpoint;
        $this->statusCode = $statusCode;
        $this->serviceName = $serviceName;
        
        $detailedMessage = sprintf(
            'API call failed to %s service endpoint: %s%s. %s',
            $serviceName,
            $endpoint,
            $statusCode ? " (Status code: $statusCode)" : "",
            $message
        );
        
        parent::__construct($detailedMessage, 0, $previous);
    }
    
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
    
    public function getServiceName(): string
    {
        return $this->serviceName;
    }
    
    public static function spoonacular(
        string $endpoint, 
        string $message = "Spoonacular API request failed", 
        ?int $statusCode = null, 
        ?Throwable $previous = null
    ): self {
        return new static($endpoint, $message, 'Spoonacular', $statusCode, $previous);
    }
}