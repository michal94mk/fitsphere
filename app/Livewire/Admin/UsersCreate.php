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
    public $specialization;
    public $description;
    public $image;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:admin,user,trainer',
        'specialization' => 'nullable|required_if:role,trainer|string|max:255',
        'description' => 'nullable|required_if:role,trainer|string',
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
        
        if ($this->role === 'trainer') {
            $user->specialization = $this->specialization;
            $user->description = $this->description;
            
            if ($this->image) {
                $imagePath = $this->image->store('trainers', 'public');
                $user->image = $imagePath;
            }
        }
        
        $user->save();
        
        session()->flash('success', 'Użytkownik został pomyślnie utworzony.');
        return redirect()->route('admin.users.index');
    }
    
    public function updatedRole()
    {
        // Resetuj dane trainera jeśli zmieniono rolę na inną niż trainer
        if ($this->role !== 'trainer') {
            $this->specialization = null;
            $this->description = null;
            $this->image = null;
        }
    }
    
    #[Layout('layouts.admin', ['header' => 'Dodaj nowego użytkownika'])]
    public function render()
    {
        return view('livewire.admin.users-create');
    }
} 