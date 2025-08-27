<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class UsersEdit extends Component
{
    use WithFileUploads, HasFlashMessages;
    
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];
    public $photo = null;
    public $currentImage = '';
    public $existing_photo = null;
    public $changePassword = false;
    public $provider = null;
    public $provider_id = null;
    
    public function mount($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = explode(',', $user->role);
        $this->currentImage = $user->image;
        $this->provider = $user->provider;
        $this->provider_id = $user->provider_id;
        
        // Set existing_photo based on currentImage
        if ($this->currentImage) {
            $this->existing_photo = asset('storage/' . $this->currentImage);
        }
    }
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|regex:/^[\pL\s\-\']+$/u',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,user,trainer',
            'password' => $this->changePassword ? 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/' : 'nullable',
            'photo' => 'nullable|image|max:1024',
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
            'roles.required' => __('validation.user.role.required'),
            'roles.min' => 'Musisz wybrać przynajmniej jedną rolę.',
            'roles.*.in' => __('validation.user.role.in'),
            'photo.image' => __('validation.user.image.image'),
            'photo.max' => __('validation.user.image.max', ['max' => 1024]),
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            $user = User::findOrFail($this->userId);
            
            $imagePath = $user->image;
            $imageChanged = false;
            
            if ($this->photo) {
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
                $imagePath = $this->photo->store('images/users', 'public');
                $imageChanged = true;
            }
            
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = implode(',', $this->roles);
            
            if ($this->changePassword && $this->password) {
                $user->password = Hash::make($this->password);
            }
            
            if ($imagePath) {
                $user->image = $imagePath;
            }
            
            $user->save();
            
            $this->setSuccessMessage(__('admin.user_updated'));
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.user_update_error', ['error' => $e->getMessage()]));
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    public function updatedChangePassword()
    {
        if (!$this->changePassword) {
            $this->password = '';
            $this->password_confirmation = '';
        }
    }
    
    public function canChangePassword()
    {
        return !$this->provider; // Only allow password change for non-social users
    }

    public function removePhoto()
    {
        if ($this->currentImage) {
            try {
                if (Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                
                $user = User::findOrFail($this->userId);
                $user->image = null;
                $user->save();
                
                $this->currentImage = null;
                $this->existing_photo = null;
                
                $this->photo = null;
                
                $this->setSuccessMessage(__('admin.photo_removed'));
            } catch (\Exception $e) {
                $this->setErrorMessage(__('admin.photo_remove_error', ['error' => $e->getMessage()]));
            }
        }
    }

    #[Layout('layouts.admin', ['header' => 'Edit User'])]
    public function render()
    {
        return view('livewire.admin.users-edit');
    }
} 