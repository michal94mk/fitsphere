<?php

namespace App\Livewire;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class BecomeTrainer extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $description = '';
    public $image;
    public $success = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:trainers',
        'password' => 'required|string|min:8|confirmed',
        'specialization' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:1024',
    ];
    
    public function save()
    {
        $this->validate();
        
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('trainers', 'public');
        }
        
        Trainer::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'specialization' => $this->specialization,
            'description' => $this->description,
            'image' => $imagePath,
            'is_approved' => false,
        ]);
        
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'specialization', 'description', 'image']);
        $this->success = true;
    }
    
    public function render()
    {
        return view('livewire.become-trainer')
            ->layout('layouts.blog', ['title' => 'Zostań naszym trenerem']);
    }
} 