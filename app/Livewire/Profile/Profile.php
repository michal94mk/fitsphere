<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.profile.profile')->layout('layouts.blog');
    }
}
