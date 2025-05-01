<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule as FormRule;

class TrainersEdit extends Component
{
    use WithFileUploads;
    
    public $trainerId;
    
    #[FormRule('required|string|max:255', message: 'Name is required.')]
    public $name = '';
    
    public $email = '';
    
    public $password = '';
    
    public $password_confirmation = '';
    
    #[FormRule('required|string|max:255', message: 'Specialization is required.')]
    public $specialization = '';
    
    #[FormRule('nullable|string')]
    public $description = '';
    
    #[FormRule('nullable|image|max:1024', message: 'Photo must be an image with maximum size of 1MB.')]
    public $photo = null;
    
    public $currentImage = '';
    public $existing_photo = null;
    public $changePassword = false;
    
    #[FormRule('boolean')]
    public $is_approved = false;
    
    #[FormRule('nullable|string')]
    public $biography = '';
    
    #[FormRule('nullable|integer|min:0|max:100')]
    public $experience = 0;

    public function mount($id)
    {
        $this->trainerId = $id;
        $trainer = Trainer::findOrFail($id);
        
        $this->name = $trainer->name;
        $this->email = $trainer->email;
        $this->specialization = $trainer->specialization ?? '';
        $this->description = $trainer->description ?? '';
        $this->currentImage = $trainer->image;
        $this->biography = $trainer->bio ?? '';
        $this->is_approved = $trainer->is_approved;
        $this->experience = $trainer->experience ?? 0;
        
        // Set existing_photo based on currentImage
        if ($this->currentImage) {
            $this->existing_photo = asset('storage/' . $this->currentImage);
        }
    }
    
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('trainers')->ignore($this->trainerId)],
            'password' => $this->changePassword ? 'required|string|min:8|confirmed' : 'nullable',
        ];
    }
    
    public function messages()
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'password.required' => 'Password is required when changing password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Edit Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-edit');
    }

    public function save()
    {
        if ($this->changePassword) {
            $this->validate();
        } else {
            $this->validate([
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('trainers')->ignore($this->trainerId)],
            ]);
        }
        
        try {
            $trainer = Trainer::findOrFail($this->trainerId);
            
            $imagePath = $trainer->image;
            if ($this->photo) {
                // Remove old image if exists
                if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                    // Delete the old image file
                    Storage::disk('public')->delete($trainer->image);
                }
                $imagePath = $this->photo->store('trainers', 'public');
            }
            
            $trainer->name = $this->name;
            $trainer->email = $this->email;
            if ($this->changePassword && $this->password) {
                $trainer->password = Hash::make($this->password);
            }
            $trainer->specialization = $this->specialization;
            $trainer->description = $this->description;
            $trainer->bio = $this->biography;
            if ($imagePath) {
                $trainer->image = $imagePath;
            }
            $trainer->is_approved = $this->is_approved;
            $trainer->experience = $this->experience;
            
            $trainer->save();
            
            session()->flash('success', 'Trainer information has been updated!');
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the trainer: ' . $e->getMessage());
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    public function removePhoto()
    {
        if ($this->currentImage) {
            try {
                // Delete the old image file from storage if it exists
                if (Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                
                // Update the database to remove the image reference
                $trainer = Trainer::findOrFail($this->trainerId);
                $trainer->image = null;
                $trainer->save();
                
                // Update local properties
                $this->currentImage = null;
                $this->existing_photo = null;
                
                // Reset the new photo too if it exists
                $this->photo = null;
                
                session()->flash('success', 'Photo has been removed.');
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to remove photo: ' . $e->getMessage());
            }
        }
    }
    
    public function toggleChangePassword()
    {
        $this->changePassword = !$this->changePassword;
        if (!$this->changePassword) {
            $this->password = '';
            $this->password_confirmation = '';
        }
    }
} 