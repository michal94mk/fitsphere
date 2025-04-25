<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Trainer;
use Livewire\Attributes\Layout;

class UsersCreate extends Component
{
    use WithFileUploads;
    
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'user';
    public $image;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:admin,user',
        'image' => 'nullable|image|max:1024'
    ];
    
    public function store()
    {
        $this->validate();
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->role = $this->role;
        
        if ($this->image) {
            $imagePath = $this->image->store('users', 'public');
            $user->image = $imagePath;
        }
        
        $user->save();
        
        session()->flash('success', 'Użytkownik został pomyślnie utworzony.');
        return redirect()->route('admin.users.index');
    }
    
    #[Layout('layouts.admin', ['header' => 'Dodaj nowego użytkownika'])]
    public function render()
    {
        return view('livewire.admin.users-create');
    }
} 