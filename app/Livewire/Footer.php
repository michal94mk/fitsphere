<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscriber;
use App\Mail\SubscriptionConfirmation;

class Footer extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    public function subscribe()
    {
        $this->validate();

        Subscriber::create(['email' => $this->email]);

        Mail::to($this->email)->send(new SubscriptionConfirmation());

        $this->dispatch('subscriptionSuccess');

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.footer');
    }
}
