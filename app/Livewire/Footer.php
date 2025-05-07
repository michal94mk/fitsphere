<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscriber;
use App\Mail\SubscriptionConfirmation;
use App\Services\EmailService;
use App\Services\LogService;
use App\Exceptions\EmailSendingException;
use Illuminate\Support\Facades\DB;

class Footer extends Component
{
    public string $email = '';
    
    protected $emailService;
    protected $logService;
    
    public function boot()
    {
        $this->emailService = app(EmailService::class);
        $this->logService = app(LogService::class);
    }

    protected array $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    public function subscribe()
    {
        try {
            $this->validate();
            
            DB::beginTransaction();
            $subscriber = $this->createSubscriber();
            
            try {
                $this->sendConfirmationEmail();
            } catch (EmailSendingException $e) {
                // If email fails, log it but continue with subscription
                $this->logEmailFailure($e);
            }
            
            DB::commit();
            $this->showSuccessAndReset();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation exceptions are already handled by Livewire
            throw $e;
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            $this->handleGeneralException($e);
        }
    }

    protected function createSubscriber()
    {
        try {
            return Subscriber::create(['email' => $this->email]);
        } catch (\Throwable $e) {
            $this->logService->exception($e, 'Failed to create subscriber');
            throw $e; // Re-throw to be handled by parent try-catch
        }
    }

    protected function sendConfirmationEmail()
    {
        $result = $this->emailService->send(
            $this->email, 
            new SubscriptionConfirmation()
        );
        
        if ($result['status'] !== 'success') {
            throw new EmailSendingException(
                $this->email,
                SubscriptionConfirmation::class,
                $result['message'] ?? 'Unknown error'
            );
        }
    }

    protected function logEmailFailure(EmailSendingException $e)
    {
        $this->logService->exception($e, 'Subscription confirmation email failed', [
            'subscriber_email' => $this->email
        ]);
    }

    protected function showSuccessAndReset()
    {
        // Dispatch success event to show confirmation message
        $this->dispatch('subscriptionSuccess');
        
        // Reset the form
        $this->reset('email');
    }

    protected function handleGeneralException(\Throwable $e)
    {
        // Log the error with detailed information using LogService
        $this->logService->exception($e, 'Error in subscription process', [
            'email' => $this->email
        ]);
        
        // Add validation error to the form
        $this->addError('email', __('footer.subscription_error'));
    }

    public function render()
    {
        return view('livewire.footer');
    }
}
