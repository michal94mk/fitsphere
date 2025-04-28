<?php

namespace App\Services;

use App\Exceptions\EmailSendingException;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailService
{
    /**
     * @throws EmailSendingException
     */
    public function send(
        string $recipient, 
        Mailable $mailable, 
        string $successMessage = 'Email sent successfully.',
        bool $throwException = false
    ): array {
        try {
            Mail::to($recipient)->send($mailable);
            
            return [
                'status' => 'success',
                'message' => $successMessage
            ];
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            
            Log::error('Email sending failed', [
                'recipient' => $recipient,
                'mailable' => get_class($mailable),
                'error' => $errorMessage,
                'trace' => $e->getTraceAsString()
            ]);
            
            $emailException = new EmailSendingException(
                $recipient,
                get_class($mailable),
                $errorMessage,
                $e
            );
            
            if ($throwException) {
                throw $emailException;
            }
            
            return [
                'status' => 'error',
                'message' => $emailException->getMessage(),
                'exception' => $emailException
            ];
        }
    }
} 