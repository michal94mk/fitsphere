<?php

namespace App\Livewire\Admin\Traits;

trait HasFlashMessages
{
    public $successMessage = '';
    public $errorMessage = '';
    public $infoMessage = '';
    
    /**
     * Set success message
     */
    public function setSuccessMessage(string $message): void
    {
        $this->successMessage = $message;
        $this->errorMessage = '';
        $this->infoMessage = '';
        
        // Set session flash for FlashMessages component
        session()->flash('success', $message);
        
        // Dispatch event to refresh FlashMessages component
        $this->dispatch('flash', type: 'success', message: $message);
    }
    
    /**
     * Set error message
     */
    public function setErrorMessage(string $message): void
    {
        $this->errorMessage = $message;
        $this->successMessage = '';
        $this->infoMessage = '';
        
        // Set session flash for FlashMessages component
        session()->flash('error', $message);
        
        // Dispatch event to refresh FlashMessages component
        $this->dispatch('flash', type: 'error', message: $message);
    }
    
    /**
     * Set info message
     */
    public function setInfoMessage(string $message): void
    {
        $this->infoMessage = $message;
        $this->successMessage = '';
        $this->errorMessage = '';
        
        // Set session flash for FlashMessages component
        session()->flash('info', $message);
        
        // Dispatch event to refresh FlashMessages component
        $this->dispatch('flash', type: 'info', message: $message);
    }
    
    /**
     * Clear all messages
     */
    public function clearMessages(): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->infoMessage = '';
    }
    
    /**
     * Check if any messages are present
     */
    public function hasMessages(): bool
    {
        return !empty($this->successMessage) || !empty($this->errorMessage) || !empty($this->infoMessage);
    }
} 