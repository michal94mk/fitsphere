<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class TrainersCreate extends Component
{
    use WithFileUploads;
    
    #[Rule('required|string|max:255', message: 'Name is required.')]
    public $name = '';
    
    #[Rule('required|string|email|max:255|unique:trainers', message: 'This email is already taken or invalid.')]
    public $email = '';
    
    #[Rule('required|string|min:8|confirmed', message: 'Password must be at least 8 characters.')]
    public $password = '';
    
    public $password_confirmation = '';
    
    #[Rule('required|string|max:255', message: 'Specialization is required.')]
    public $specialization = '';
    
    #[Rule('nullable|string')]
    public $description = '';
    
    #[Rule('nullable|string')]
    public $biography = '';
    
    #[Rule('nullable|image|max:1024', message: 'Photo must be an image with maximum size of 1MB.')]
    public $photo = null;
    
    #[Rule('boolean')]
    public $is_approved = false;
    
    #[Rule('nullable|integer|min:0|max:100')]
    public $experience = 0;

    // Define validation rules matching Trainer model's fillable attributes
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:trainers',
        'password' => 'required|string|min:8|confirmed',
        'specialization' => 'required|string|max:255',
        'description' => 'nullable|string',
        'biography' => 'nullable|string',
        'photo' => 'nullable|image|max:1024', // 1MB Max
        'is_approved' => 'boolean',
        'experience' => 'nullable|integer|min:0|max:100',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'specialization.required' => 'Specialization is required.',
        'photo.image' => 'Selected file must be an image.',
        'photo.max' => 'Photo cannot be larger than 1MB.',
    ];

    #[Layout('layouts.admin', ['header' => 'Add New Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-create');
    }

    public function save()
    {
        $this->validate();
        
        try {
            $imagePath = null;
            if ($this->photo) {
                $imagePath = $this->photo->store('trainers', 'public');
            }
            
            Trainer::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'specialization' => $this->specialization,
                'description' => $this->description,
                'bio' => $this->biography,
                'image' => $imagePath,
                'is_approved' => $this->is_approved,
                'experience' => $this->experience,
            ]);
            
            $successMessage = $this->is_approved 
                ? 'Trainer has been successfully added and approved!' 
                : 'Trainer has been successfully added! They need to verify their email address to activate the account.';
            
            session()->flash('success', $successMessage);
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while adding the trainer: ' . $e->getMessage());
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }
} 