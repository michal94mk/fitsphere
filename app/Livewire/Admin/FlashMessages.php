<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;

class FlashMessages extends Component
{
    public array $messages = [];
    public bool $show = false;
    
    public function mount()
    {
        // Check for existing session messages
        if (session('success') || session('error') || session('info')) {
            $this->show = true;
            
            if (session('success')) {
                $this->messages[] = [
                    'type' => 'success',
                    'message' => session('success')
                ];
            }
            
            if (session('error')) {
                $this->messages[] = [
                    'type' => 'error',
                    'message' => session('error')
                ];
            }
            
            if (session('info')) {
                $this->messages[] = [
                    'type' => 'info',
                    'message' => session('info')
                ];
            }
        }
    }
    
    #[On('flash')]
    public function addMessage($type, $message)
    {
        // Check if message already exists to avoid duplicates
        $exists = collect($this->messages)->contains(function ($msg) use ($type, $message) {
            return $msg['type'] === $type && $msg['message'] === $message;
        });
        
        if (!$exists) {
            $this->messages[] = [
                'type' => $type,
                'message' => $message
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
    
    public function hideMessages()
    {
        $this->show = false;
    }
    
    public function render()
    {
        return view('livewire.admin.flash-messages');
    }
} 