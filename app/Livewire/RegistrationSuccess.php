<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class RegistrationSuccess extends Component
{
    #[Layout('layouts.blog', ['title' => 'Rejestracja zakończona pomyślnie'])]
    public function render()
    {
        return view('livewire.registration-success');
    }
} 