<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TrainersEdit extends Component
{
    use WithFileUploads, HasFlashMessages;
    
    public $trainerId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $experience = 0;
    public $description = '';
    public $biography = '';
    public $is_approved = false;
    public $photo = null;
    public $existing_photo = null;
    public $changePassword = false;
    public $provider = null;
    
    public function mount($id)
    {
        $this->trainerId = $id;
        $trainer = User::where('role', 'like', '%trainer%')->findOrFail($id);
        
        $this->name = $trainer->name;
        $this->email = $trainer->email;
        $this->specialization = $trainer->specialization ?? '';
        $this->experience = $trainer->experience ?? 0;
        $this->description = $trainer->description ?? '';
        $this->biography = $trainer->biography ?? '';
        $this->is_approved = $trainer->is_approved ?? false;
        $this->provider = $trainer->provider;
        
        if ($trainer->image) {
            $this->existing_photo = asset('storage/' . $trainer->image);
        }
    }
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email,' . $this->trainerId,
            'specialization' => 'required|string|min:3|max:100',
            'experience' => 'required|integer|min:0|max:50',
            'description' => 'nullable|string|max:500',
            'biography' => 'nullable|string|max:2000',
            'password' => $this->changePassword ? 'required|string|min:8|confirmed' : 'nullable',
            'photo' => 'nullable|image|max:1024',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $trainer = User::findOrFail($this->trainerId);

            if ($this->photo) {
                if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                    Storage::disk('public')->delete($trainer->image);
                }
                $imagePath = $this->photo->store('images/trainers', 'public');
                $trainer->image = $imagePath;
            }

            $trainer->name = $this->name;
            $trainer->email = $this->email;
            $trainer->specialization = $this->specialization;
            $trainer->experience = $this->experience;
            $trainer->description = $this->description;
            $trainer->biography = $this->biography;
            $trainer->is_approved = $this->is_approved;
            
            if ($this->changePassword && $this->password) {
                $trainer->password = Hash::make($this->password);
            }
            
            $trainer->save();

            $this->setSuccessMessage(__('admin.trainer_updated'));
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_update_error', ['error' => $e->getMessage()]));
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    public function canChangePassword()
    {
        return !$this->provider; // Only allow password change for non-social users
    }

    public function removePhoto()
    {
        if ($this->existing_photo) {
            try {
                $trainer = User::findOrFail($this->trainerId);
                
                if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                    Storage::disk('public')->delete($trainer->image);
                }
                
                $trainer->image = null;
                $trainer->save();
                
                $this->existing_photo = null;
                $this->photo = null;
                
                $this->setSuccessMessage(__('admin.photo_removed'));
            } catch (\Exception $e) {
                $this->setErrorMessage(__('admin.photo_remove_error', ['error' => $e->getMessage()]));
            }
        }
    }

    #[Layout('layouts.admin', ['header' => 'Edit Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-edit');
    }
} 