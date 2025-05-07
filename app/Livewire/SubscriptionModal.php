<?php

namespace App\Livewire;

use Livewire\Component;

class SubscriptionModal extends Component
{
    public bool $isVisible = false;

    protected $listeners = ['subscriptionSuccess' => 'showModal'];


    public function showModal()
    {
        $this->isVisible = true;
    }

    public function closeModal()
    {
        $this->isVisible = false;
    }

    public function render()
    {
        return view('livewire.subscription-modal');
    }
}
