<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UsersEdit extends Component
{
    use WithFileUploads;
    
    public $user;
    public $image;
    
    protected function rules()
    {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'user.role' => 'required|in:admin,user,trainer',
            'user.specialization' => 'nullable|required_if:user.role,trainer|string|max:255',
            'user.description' => 'nullable|required_if:user.role,trainer|string',
            'image' => 'nullable|image|max:1024'
        ];
    }
    
    public function mount($id)
    {
        $this->user = User::findOrFail($id);
    }
    
    public function update()
    {
        $this->validate();
        
        // Obsługa zdjęcia
        if ($this->image) {
            // Usuń stare zdjęcie jeśli istnieje
            if ($this->user->image && Storage::disk('public')->exists($this->user->image)) {
                Storage::disk('public')->delete($this->user->image);
            }
            
            $imagePath = $this->image->store('users', 'public');
            $this->user->image = $imagePath;
        }
        
        // Resetuj dane trenera jeśli role nie jest trenerem
        if ($this->user->role !== 'trainer') {
            $this->user->specialization = null;
            $this->user->description = null;
        }
        
        $this->user->save();
        
        session()->flash('success', 'Użytkownik został pomyślnie zaktualizowany.');
        return redirect()->route('admin.users.index');
    }
    
    public function render()
    {
        return view('livewire.admin.users-edit')
            ->layout('layouts.admin', ['header' => 'Edytuj użytkownika']);
    }
} 