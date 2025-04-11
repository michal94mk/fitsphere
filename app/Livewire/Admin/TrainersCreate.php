<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class TrainersCreate extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $description = '';
    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:trainers',
        'password' => 'required|string|min:8|confirmed',
        'specialization' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:1024', // 1MB Max
    ];

    public function render()
    {
        return view('livewire.admin.trainers-create')->layout('layouts.admin', [
            'header' => 'Dodaj nowego trenera'
        ]);
    }

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
            'email_verified_at' => now(), // Automatically verify the trainer's email
        ]);
        
        session()->flash('success', 'Trener został pomyślnie dodany!');
        return redirect()->route('admin.trainers.index');
    }
} 