<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Mail\ContactFormMail;
use App\Services\EmailService;
use App\Exceptions\EmailSendingException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class ContactPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    
    protected $emailService;
    
    public function boot()
    {
        $this->emailService = app(EmailService::class);
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
        } catch (\Throwable $e) {
            $this->handleGeneralError($e);
        }
    }

    protected function sendContactEmail(array $data)
    {
        $result = $this->emailService->send(
            'admin@reply.com',
            new ContactFormMail($data)
        );
        
        if ($result['status'] !== 'success') {
            throw new EmailSendingException(
                $this->email,
                ContactFormMail::class,
                $result['message'] ?? 'Unknown error'
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
        // Log the specific email error
        Log::warning('Contact form email failed', [
            'from' => $this->email,
            'name' => $this->name,
            'error' => $e->getMessage(),
            'recipient' => $e->getRecipient(),
            'mailable' => $e->getMailableClass()
        ]);
        
        session()->flash('error', __('contact.email_error'));
    }

    protected function handleGeneralError(\Throwable $e)
    {
        // Log detailed exception information for unexpected errors
        Log::error('Error in contact form submission', [
            'from' => $this->email,
            'name' => $this->name,
            'error' => $e->getMessage(),
            'class' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('error', __('contact.error'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.contact-page');
    }
}
