<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class FlashMessages extends Component
{
    public array $messages = [];
    public bool $show = false;
    
    public function mount()
    {
        // Check for existing session messages
        $this->loadSessionMessages();
    }
    
    private function loadSessionMessages()
    {
        $this->messages = [];
        
        // Don't show success/error messages on post detail pages (they have local messages)
        $isPostDetailPage = request()->routeIs('post.show');
        
        if (session('success') && !$isPostDetailPage) {
            $this->messages[] = [
                'type' => 'success',
                'message' => session('success'),
                'icon' => 'success'
            ];
        }
        
        if (session('error') && !$isPostDetailPage) {
            $this->messages[] = [
                'type' => 'error',
                'message' => session('error'),
                'icon' => 'error'
            ];
        }
        
        if (session('info')) {
            $this->messages[] = [
                'type' => 'info',
                'message' => session('info'),
                'icon' => 'info'
            ];
        }
        
        if (session('warning')) {
            $this->messages[] = [
                'type' => 'warning',
                'message' => session('warning'),
                'icon' => 'warning'
            ];
        }
        
        if (session('verification_sent')) {
            $this->messages[] = [
                'type' => 'info',
                'message' => session('verification_sent'),
                'icon' => 'info'
            ];
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