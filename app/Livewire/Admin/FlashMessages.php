<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Admin Flash Messages Component
 * 
 * This component manages the flash notifications displayed in the admin panel.
 * It handles session-based messages, Livewire events, and provides message stacking capability.
 */
class FlashMessages extends Component
{
    /**
     * Array of notification messages to be displayed
     * 
     * @var array
     */
    public array $messages = [];
    
    /**
     * Flag controlling the visibility of messages
     * 
     * @var bool
     */
    public bool $show = false;
    
    /**
     * Initialize the component with any session flash messages
     * 
     * @return void
     */
    public function mount()
    {
        // Check for existing session messages
        if (session('success') || session('error')) {
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
        }
    }
    
    /**
     * Add a new flash message via Livewire event
     * 
     * This method can be triggered by other components via Livewire events
     * to display notifications in the admin interface.
     * 
     * @param string $type Type of message ('success' or 'error')
     * @param string $message The notification text
     * @return void
     */
    #[On('flash')]
    public function addMessage($type, $message)
    {
        $this->messages[] = [
            'type' => $type,
            'message' => $message
        ];
        
        $this->show = true;
    }
    
    /**
     * Remove a specific message by its index
     * 
     * @param int $index The array index of the message to remove
     * @return void
     */
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
    
    /**
     * Hide all notification messages
     * 
     * @return void
     */
    public function hideMessages()
    {
        $this->show = false;
    }
    
    /**
     * Render the flash messages component view
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.flash-messages');
    }
} 