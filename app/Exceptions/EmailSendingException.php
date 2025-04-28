<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EmailSendingException extends Exception
{
    protected $recipient;
    protected $mailableClass;
    
    public function __construct(
        string $recipient, 
        string $mailableClass, 
        string $message = "Email sending failed", 
        ?Throwable $previous = null
    ) {
        $this->recipient = $recipient;
        $this->mailableClass = $mailableClass;
        
        $detailedMessage = sprintf(
            'Failed to send email to recipient: %s (message type: %s). %s', 
            $recipient, 
            $mailableClass,
            $message !== "Email sending failed" ? "Details: " . $message : ""
        );
        
        parent::__construct($detailedMessage, 0, $previous);
    }
    
    public function getRecipient(): string
    {
        return $this->recipient;
    }
    
    public function getMailableClass(): string
    {
        return $this->mailableClass;
    }
} 