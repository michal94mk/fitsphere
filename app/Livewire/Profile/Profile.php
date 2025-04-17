<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Profile extends Component
{
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile.profile');
    }
}
