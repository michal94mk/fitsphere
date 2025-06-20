<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class FlashMessages extends Component
{
    public array $messages = [];
    public bool $show = false;
    
    public function mount()
    {
        // Check for existing session messages
        $this->loadSessionMessages();
        
        // Clear session flash messages after loading to prevent reloading
        $this->clearSessionMessages();
    }
    
    private function clearSessionMessages()
    {
        $isPostDetailPage = request()->routeIs('post.show');
        $isAdminPage = request()->routeIs('admin.*');
        
        // Clear authentication-related error messages if user is not logged in
        if (!Auth::check() && session('error')) {
            $errorMessage = session('error');
            $isAuthRelatedError = str_contains($errorMessage, 'administrator access') ||
                                str_contains($errorMessage, 'dostęp administratora') ||
                                str_contains($errorMessage, 'login') ||
                                str_contains($errorMessage, 'zaloguj') ||
                                str_contains($errorMessage, 'access denied') ||
                                str_contains($errorMessage, 'dostęp zabroniony') ||
                                str_contains($errorMessage, 'session') ||
                                str_contains($errorMessage, 'sesja');
            
            if ($isAuthRelatedError) {
                session()->forget('error');
            }
        }
        
        // Always clear messages after display except on post detail pages
        if (!$isPostDetailPage) {
            session()->forget(['success', 'error']);
        }
        session()->forget(['info', 'warning', 'verification_sent']);
    }
    
    private function loadSessionMessages()
    {
        $this->messages = [];
        
        // Don't show success/error messages on post detail pages (they have local messages)
        $isPostDetailPage = request()->routeIs('post.show');
        // Admin pages should show all messages globally
        $isAdminPage = request()->routeIs('admin.*');
        
        // Helper function to add message if it doesn't exist
        $addUniqueMessage = function($type, $message, $icon = null) {
            $exists = collect($this->messages)->contains(function ($msg) use ($type, $message) {
                return $msg['type'] === $type && $msg['message'] === $message;
            });
            
            if (!$exists) {
                $this->messages[] = [
                    'type' => $type,
                    'message' => $message,
                    'icon' => $icon ?? $type
                ];
            }
        };
        
        if (session('success') && !$isPostDetailPage) {
            $addUniqueMessage('success', session('success'), 'success');
        }
        
        // Only show error messages if session is valid or user is authenticated
        // This prevents showing middleware errors after session expiration
        if (session('error') && !$isPostDetailPage) {
            // Don't show admin access errors if user is not authenticated
            $errorMessage = session('error');
            $isAdminAccessError = str_contains($errorMessage, 'administrator access') || 
                                str_contains($errorMessage, 'dostęp administratora');
            
            // Also filter out other authentication-related errors when user is not logged in
            $isAuthError = str_contains($errorMessage, 'login') ||
                          str_contains($errorMessage, 'zaloguj') ||
                          str_contains($errorMessage, 'access denied') ||
                          str_contains($errorMessage, 'dostęp zabroniony') ||
                          str_contains($errorMessage, 'session') ||
                          str_contains($errorMessage, 'sesja');
            
            if ((!$isAdminAccessError && !$isAuthError) || Auth::check()) {
                $addUniqueMessage('error', $errorMessage, 'error');
            }
        }
        
        if (session('info')) {
            $addUniqueMessage('info', session('info'), 'info');
        }
        
        if (session('warning')) {
            $addUniqueMessage('warning', session('warning'), 'warning');
        }
        
        if (session('verification_sent')) {
            $addUniqueMessage('info', session('verification_sent'), 'info');
        }
        
        $this->show = !empty($this->messages);
    }
    
    #[On('flash')]
    public function addMessage($type, $message, $icon = null)
    {
        // Check if message already exists to avoid duplicates
        $exists = collect($this->messages)->contains(function ($msg) use ($type, $message) {
            return $msg['type'] === $type && $msg['message'] === $message;
        });
        
        if (!$exists) {
            $this->messages[] = [
                'type' => $type,
                'message' => $message,
                'icon' => $icon ?? $type
            ];
        }
        
        $this->show = true;
    }
    
    public function removeMessage($index)
    {
        // Remove the specified message
        if (isset($this->messages[$index])) {
            unset($this->messages[$index]);
            $this->messages = array_values($this->messages);
        }
        
        // Hide container if no messages remain
        if (empty($this->messages)) {
            $this->show = false;
        }
    }
    
    public function hideAllMessages()
    {
        $this->show = false;
        $this->messages = [];
    }
    
    public function render()
    {
        return view('livewire.flash-messages');
    }
} 