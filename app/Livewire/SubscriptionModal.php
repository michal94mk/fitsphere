<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Modal component for subscription success notifications
 */
class SubscriptionModal extends Component
{
    public bool $isVisible = false;

    protected $listeners = ['subscriptionSuccess' => 'showModal'];

    /**
     * Display the subscription modal
     */
    public function showModal()
    {
        $this->isVisible = true;
    }

    /**
     * Hide the subscription modal
     */
    public function closeModal()
    {
        $this->isVisible = false;
    }

    public function render()
    {
        return view('livewire.subscription-modal');
    }
}
