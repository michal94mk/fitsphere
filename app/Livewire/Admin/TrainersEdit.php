<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class TrainersEdit extends Component
{
    use WithFileUploads;
    
    public $trainerId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $description = '';
    public $photo = null;
    public $currentImage = '';
    public $existing_photo = null;
    public $changePassword = false;
    public $is_approved = false;
    public $biography = '';
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
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('trainers')->ignore($this->trainerId)],
            'specialization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:1024',
            'is_approved' => 'boolean',
            'biography' => 'nullable|string',
            'experience' => 'nullable|integer|min:0|max:100',
            'password' => $this->changePassword ? 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/' : 'nullable',
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
            'specialization.required' => __('validation.user.specialization.required'),
            'photo.image' => __('validation.user.image.image'),
            'photo.max' => __('validation.user.image.max', ['max' => 1024]),
            'password.required' => __('validation.user.password.required'),
            'password.min' => __('validation.user.password.min', ['min' => 8]),
            'password.confirmed' => __('validation.user.password.confirmed'),
            'password.regex' => __('validation.user.password.regex'),
        ];
    }

    public function save()
    {
        $this->validate();
        
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
            
            session()->flash('success', __('trainers.trainer_updated'));
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            session()->flash('error', __('trainers.trainer_update_error', ['error' => $e->getMessage()]));
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
                
                session()->flash('success', __('trainers.photo_removed'));
            } catch (\Exception $e) {
                session()->flash('error', __('trainers.photo_remove_error', ['error' => $e->getMessage()]));
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

    #[Layout('layouts.admin', ['header' => 'Edit Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-edit');
    }
} 