<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class UsersDashboard extends Component
{
    public $adminCount;
    public $trainerCount;
    public $userCount;
    
    public function mount()
    {
        $this->adminCount = User::where('role', 'admin')->count();
        $this->trainerCount = User::where('role', 'trainer')->count();
        $this->userCount = User::where('role', 'user')->count();
    }
    
    public function render()
    {
        return view('livewire.admin.users-dashboard')
            ->layout('layouts.admin', ['header' => 'Panel użytkowników']);
    }
} 