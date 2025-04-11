<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class UsersShow extends Component
{
    public $user;
    
    public function mount($id)
    {
        $this->user = User::findOrFail($id);
    }
    
    public function render()
    {
        return view('livewire.admin.users-show')
            ->layout('layouts.admin', ['header' => 'Szczegóły użytkownika']);
    }
} 