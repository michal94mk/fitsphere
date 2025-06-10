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

    /**
     * Enhanced validation rules with proper security limits
     */
    protected array $rules = [
        'name' => [
            'required',
            'string',
            'min:2',
            'max:50',
                            'regex:/^[\pL\s\-\'\.À-ſ]+$/u', // International characters support
        ],
        'email' => [
            'required',
            'email:rfc,dns',
            'max:100',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        ],
        'message' => [
            'required',
            'string',
            'min:10',
            'max:1000',
                            'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]+$/u',
        ],
    ];

    /**
     * Custom validation attributes
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => __('validation.attributes.full_name'),
            'email' => __('validation.attributes.email_address'),
            'message' => __('validation.attributes.message'),
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            // Name validation
            'name.required' => __('validation.contact.name.required'),
            'name.min' => __('validation.contact.name.min'),
            'name.max' => __('validation.contact.name.max'),
            'name.regex' => __('validation.contact.name.format'),
            
            // Email validation
            'email.required' => __('validation.contact.email.required'),
            'email.email' => __('validation.contact.email.format'),
            'email.max' => __('validation.contact.email.max'),
            'email.regex' => __('validation.contact.email.format'),
            
            // Message validation
            'message.required' => __('validation.contact.message.required'),
            'message.min' => __('validation.contact.message.min'),
            'message.max' => __('validation.contact.message.max'),
            'message.regex' => __('validation.contact.message.format'),
        ];
    }

    /**
     * Real-time validation with input sanitization
     */
    public function updated($propertyName)
    {
        // Sanitize input first
        $this->sanitizeInput($propertyName);
        
        // Clear previous errors for this field
        $this->resetErrorBag($propertyName);
        
        // Validate only the updated field
        $this->validateOnly($propertyName);
    }

    /**
     * Sanitize user input for security
     */
    private function sanitizeInput(string $propertyName): void
    {
        switch($propertyName) {
            case 'name':
                $this->name = trim(strip_tags($this->name));
                break;
            case 'email':
                $this->email = trim(strtolower(strip_tags($this->email)));
                break;
            case 'message':
                // Allow basic HTML but strip dangerous tags
                $this->message = trim(strip_tags($this->message, '<br><p><strong><em><u>'));
                // Remove excessive whitespace
                $this->message = preg_replace('/\s+/', ' ', $this->message);
                break;
        }
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
