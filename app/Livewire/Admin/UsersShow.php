<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

class UsersShow extends Component
{
    public $userId;
    public $user;

    public function mount($id)
    {
        $this->userId = $id;
        $this->user = User::with(['posts', 'comments'])->findOrFail($id);
    }

    #[Layout('layouts.admin', ['header' => 'admin.user_details'])]
    public function render()
    {
        return view('livewire.admin.users-show');
    }
}
