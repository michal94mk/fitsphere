<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscriber;
use App\Mail\SubscriptionConfirmation;
use App\Services\EmailService;
use App\Exceptions\EmailSendingException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Footer extends Component
{
    public string $email = '';
    
    protected $emailService;
    
    public function boot()
    {
        $this->emailService = app(EmailService::class);
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
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleGeneralException($e);
        }
    }

    protected function createSubscriber()
    {
        return Subscriber::create(['email' => $this->email]);
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
        Log::warning('Subscription confirmation email failed', [
            'subscriber_email' => $this->email,
            'error' => $e->getMessage(),
            'recipient' => $e->getRecipient(),
            'mailable' => $e->getMailableClass()
        ]);
    }

    protected function showSuccessAndReset()
    {
        // Dispatch success event to show confirmation message
        $this->dispatch('subscriptionSuccess');
        
        // Reset the form
        $this->reset('email');
    }

    protected function handleGeneralException(\Exception $e)
    {
        // Log the error with detailed information
        Log::error('Error in subscription process', [
            'email' => $this->email,
            'error' => $e->getMessage(),
            'class' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Add validation error to the form
        $this->addError('email', __('footer.subscription_error'));
    }

    public function render()
    {
        return view('livewire.footer');
    }
}
