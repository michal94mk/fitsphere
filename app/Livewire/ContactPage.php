<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Mail\ContactFormMail;
use App\Services\EmailService;
use App\Exceptions\EmailSendingException;
use App\Services\LogService;
use Throwable;

/**
 * Handles contact form submission and email sending
 */
class ContactPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    
    protected $emailService;
    protected $logService;
    
    public function boot()
    {
        $this->emailService = app(EmailService::class);
        $this->logService = app(LogService::class);
    }

    protected array $rules = [
        'name'    => 'required|string|min:3|max:255',
        'email'   => 'required|email|min:3|max:255',
        'message' => 'required|string|min:3|max:1000',
    ];

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    /**
     * Process form submission and send email
     */
    public function send()
    {
        try {
            $validatedData = $this->validate();
            $this->sendContactEmail($validatedData);
            $this->showSuccessMessage();
            $this->resetForm();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation exceptions are already handled by Livewire
            throw $e;
        } catch (EmailSendingException $e) {
            $this->handleEmailError($e);
        } catch (Throwable $e) {
            $this->handleGeneralError($e);
        }
    }

    protected function sendContactEmail(array $data)
    {
        try {
            $result = $this->emailService->sendContactFormEmail(
                $data['name'],
                $data['email'], 
                $data['message']
            );
            
            if (!$result) {
                throw new EmailSendingException(
                    config('mail.from.address'),
                    'ContactFormMail',
                    'Failed to queue contact form email'
                );
            }
        } catch (\Exception $e) {
            // Log using proper service
            $this->logService->exception($e, 'Contact form email sending failed', [
                'user_name' => $this->name,
                'user_email' => $this->email,
            ]);
            
            throw new EmailSendingException(
                config('mail.from.address'),
                'ContactFormMail',
                'Contact form email failed',
                $e
            );
        }
    }

    protected function showSuccessMessage()
    {
        session()->flash('success', __('contact.success'));
    }

    protected function resetForm()
    {
        $this->reset(['name', 'email', 'message']);
    }

    protected function handleEmailError(EmailSendingException $e)
    {
        // Email exceptions are already logged in sendContactEmail method
        session()->flash('error', __('contact.email_error'));
    }

    protected function handleGeneralError(Throwable $e)
    {
        // Log detailed exception information for unexpected errors
        $this->logService->exception($e, 'Unexpected error in contact form submission', [
            'user_email' => $this->email,
            'user_name' => $this->name,
        ]);
        
        session()->flash('error', __('contact.error'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.contact-page');
    }
}
