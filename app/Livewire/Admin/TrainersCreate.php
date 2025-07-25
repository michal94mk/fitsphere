<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

class TrainersCreate extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $experience = 0;
    public $description = '';
    public $biography = '';
    public $photo = null;
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|min:3|max:100',
            'experience' => 'required|integer|min:0|max:50',
            'description' => 'nullable|string|max:500',
            'biography' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:1024',
        ];
    }

    public function store()
    {
        $this->validate();
        
        $trainer = new User();
        $trainer->name = $this->name;
        $trainer->email = $this->email;
        $trainer->password = Hash::make($this->password);
        $trainer->role = 'trainer';
        $trainer->specialization = $this->specialization;
        $trainer->experience = $this->experience;
        $trainer->description = $this->description;
        $trainer->biography = $this->biography;
        $trainer->is_approved = false;
        
        if ($this->photo) {
            $imagePath = $this->photo->store('images/trainers', 'public');
            $trainer->image = $imagePath;
        }
        
        $trainer->save();
        
        session()->flash('success', __('admin.trainer_created_success'));
        return redirect()->route('admin.trainers.index');
    }

    #[Layout('layouts.admin', ['header' => 'Add New Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-create');
    }
} 