<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class UsersIndex extends Component
{
    use WithPagination;
    
    public $role = 'all';
    
    public function mount($role = 'all')
    {
        $this->role = $role;
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Usuń zdjęcie użytkownika jeśli istnieje
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }
        
        $user->delete();
        session()->flash('success', 'Użytkownik został pomyślnie usunięty.');
    }
    
    public function render()
    {
        $query = User::query();
        
        if ($this->role != 'all') {
            $query->where('role', $this->role);
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('livewire.admin.users-index', [
            'users' => $users,
            'role' => $this->role
        ])->layout('layouts.admin', ['header' => 'Lista użytkowników']);
    }
} 