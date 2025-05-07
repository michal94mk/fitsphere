<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class TrainersCreate extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $description = '';
    public $biography = '';
    public $photo = null;
    public $is_approved = false;
    public $experience = 0;

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
                ? __('trainers.trainer_added_approved') 
                : __('trainers.trainer_added_pending');
            
            session()->flash('success', $successMessage);
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            session()->flash('error', __('trainers.trainer_add_error', ['error' => $e->getMessage()]));
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|regex:/^[\pL\s\-\']+$/u',
            'email' => 'required|string|email:rfc,dns|max:255|unique:trainers',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'specialization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|max:1024', // 1MB Max
            'is_approved' => 'boolean',
            'experience' => 'nullable|integer|min:0|max:100',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => __('validation.user.name.required'),
            'name.regex' => __('validation.user.name.regex'),
            'email.required' => __('validation.user.email.required'),
            'email.email' => __('validation.user.email.email'),
            'email.unique' => __('validation.user.email.unique'),
            'password.required' => __('validation.user.password.required'),
            'password.min' => __('validation.user.password.min', ['min' => 8]),
            'password.confirmed' => __('validation.user.password.confirmed'),
            'password.regex' => __('validation.user.password.regex'),
            'specialization.required' => __('validation.user.specialization.required'),
            'photo.image' => __('validation.user.image.image'),
            'photo.max' => __('validation.user.image.max', ['max' => 1024]),
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Add New Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-create');
    }
} 