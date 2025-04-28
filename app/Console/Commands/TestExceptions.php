<?php

namespace App\Console\Commands;

use App\Exceptions\ApiException;
use App\Exceptions\EmailSendingException;
use App\Mail\SubscriptionConfirmation;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestExceptions extends Command
{
    protected $signature = 'test:exceptions {type=all : The type of exception to test (api, email, all)}';
    protected $description = 'Test the custom exceptions in the application';

    public function handle(EmailService $emailService)
    {
        $type = $this->argument('type');
        
        $this->info('Testing exceptions...');
        
        try {
            if ($type === 'api' || $type === 'all') {
                $this->testApiException();
            }
            
            if ($type === 'email' || $type === 'all') {
                $this->testEmailException($emailService);
            }
            
            $this->info('Exception tests completed. Check the Laravel log file for details.');
        } catch (\Exception $e) {
            $this->error('An unexpected error occurred: ' . $e->getMessage());
            $this->error('Check logs for more details.');
        }
        
        return Command::SUCCESS;
    }
    
    protected function testApiException()
    {
        $this->info('Testing API Exception...');
        
        try {
            $this->comment('  Simulating a failed Spoonacular API call...');
            
            $exception = ApiException::spoonacular(
                '/recipes/random', 
                'Invalid API key provided',
                401
            );
            
            throw $exception;
        } catch (ApiException $e) {
            $this->warn('  Caught ApiException: ' . $e->getMessage());
            $this->info('  API Service: ' . $e->getServiceName());
            $this->info('  Endpoint: ' . $e->getEndpoint());
            $this->info('  Status code: ' . ($e->getStatusCode() ?: 'N/A'));
            
            Log::warning('Test: ' . $e->getMessage(), [
                'exception' => get_class($e)
            ]);
            
            $this->info('  Exception successfully logged.');
        }
        
        try {
            $this->comment('  Simulating a real HTTP request failure...');
            
            $response = Http::withOptions(['timeout' => 1])
                ->get('https://non-existent-domain-123456789.example.com/api');
                
            if (!$response->successful()) {
                throw ApiException::spoonacular(
                    '/non-existent-endpoint', 
                    'Connection timed out',
                    $response->status()
                );
            }
        } catch (ApiException $e) {
            $this->warn('  Caught ApiException from HTTP call: ' . $e->getMessage());
            
            report($e);
            
            $this->info('  HTTP failure exception successfully reported.');
        } catch (\Exception $e) {
            $this->warn('  Caught general exception (expected): ' . $e->getMessage());
            
            $apiException = ApiException::spoonacular(
                '/some/endpoint',
                'Original error: ' . $e->getMessage(),
                null
            );
            
            report($apiException);
            
            $this->info('  General exception converted to ApiException and reported.');
        }
    }
    
    protected function testEmailException(EmailService $emailService)
    {
        $this->info('Testing Email Exception...');
        
        try {
            $this->comment('  Simulating email sending to invalid address...');
            
            throw new EmailSendingException(
                'not-valid-email',
                'App\Mail\TestMail',
                'Invalid email format'
            );
        } catch (EmailSendingException $e) {
            $this->warn('  Caught EmailSendingException: ' . $e->getMessage());
            $this->info('  Recipient: ' . $e->getRecipient());
            $this->info('  Mailable: ' . $e->getMailableClass());
            
            report($e);
            
            $this->info('  Exception successfully logged.');
        }
        
        try {
            $this->comment('  Testing EmailService error handling...');
            
            $result = $emailService->send(
                'non-existing-address@example.com',
                new SubscriptionConfirmation(),
                'Test email sent',
                true
            );
        } catch (EmailSendingException $e) {
            $this->warn('  Caught EmailSendingException from service: ' . $e->getMessage());
            
            report($e);
            
            $this->info('  Email service exception successfully reported.');
        }
    }
}