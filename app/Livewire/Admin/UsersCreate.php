<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class UsersCreate extends Component
{
    use WithFileUploads, HasFlashMessages;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = ['user']; // Default to user role
    public $photo;
    
    /**
     * Real-time validation on input change
     */
    public function updated($propertyName)
    {
        // Sanitize input
        $this->sanitizeInput($propertyName);
        
        // Clear previous errors
        $this->resetErrorBag($propertyName);
        
        // Validate only the updated field
        $this->validateOnly($propertyName);
    }
    
    /**
     * Sanitize user input for security
     */
    private function sanitizeInput(string $propertyName): void
    {
        switch($propertyName) {
            case 'name':
                $this->name = trim(strip_tags($this->name));
                break;
            case 'email':
                $this->email = trim(strtolower(strip_tags($this->email)));
                break;
            case 'roles':
                // Sanitize roles array
                $this->roles = array_map(fn($role) => trim(strip_tags($role)), $this->roles);
                break;
        }
    }
    
    /**
     * Enhanced validation rules with proper security limits
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-\'\.À-ſ]+$/u',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:100',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
                'max:128',
            ],
            'roles' => [
                'required',
                'array',
                'min:1',
            ],
            'roles.*' => [
                'in:admin,user,trainer',
            ],
            'photo' => [
                'nullable',
                'image',
                'max:1024',
                'mimes:jpeg,jpg,png,webp',
                'dimensions:min_width=100,min_height=100,max_width=1500,max_height=1500',
            ],
        ];
    }
    
    /**
     * Custom validation attributes
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => __('validation.attributes.full_name'),
            'email' => __('validation.attributes.email_address'),
            'password' => __('validation.attributes.password'),
            'password_confirmation' => __('validation.attributes.password_confirmation'),
            'roles' => __('validation.attributes.user_roles'),
            'photo' => __('validation.attributes.profile_photo'),
        ];
    }
    
    /**
     * Enhanced validation messages
     */
    protected function messages()
    {
        return [
            // Name validation
            'name.required' => __('validation.user.name.required'),
            'name.min' => __('validation.user.name.min'),
            'name.max' => __('validation.user.name.max'),
            'name.regex' => __('validation.user.name.format'),
            
            // Email validation
            'email.required' => __('validation.user.email.required'),
            'email.email' => __('validation.user.email.format'),
            'email.max' => __('validation.user.email.max'),
            'email.unique' => __('validation.user.email.unique'),
            'email.regex' => __('validation.user.email.format'),
            
            // Password validation
            'password.required' => __('validation.user.password.required'),
            'password.min' => __('validation.user.password.min'),
            'password.max' => __('validation.user.password.max'),
            'password.confirmed' => __('validation.user.password.confirmed'),
            'password.regex' => __('validation.user.password.complex'),
            
            // Password confirmation
            'password_confirmation.required' => __('validation.user.password_confirmation.required'),
            'password_confirmation.min' => __('validation.user.password_confirmation.min'),
            'password_confirmation.max' => __('validation.user.password_confirmation.max'),
            
            // Role validation
            'roles.required' => __('validation.user.roles.required'),
            'roles.min' => __('validation.user.roles.min'),
            'roles.*.in' => __('validation.user.roles.invalid'),
            
            // Photo validation
            'photo.image' => __('validation.user.photo.image'),
            'photo.max' => __('validation.user.photo.max'),
            'photo.mimes' => __('validation.user.photo.mimes'),
            'photo.dimensions' => __('validation.user.photo.dimensions'),
        ];
    }
    
    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }
    
    public function removePhoto()
    {
        $this->photo = null;
    }
    
    public function store()
    {
        $this->validate();
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->role = implode(',', $this->roles);
        
        $hasImage = false;
        if ($this->photo) {
            $imagePath = $this->photo->store('images/users', 'public');
            $user->image = $imagePath;
            $hasImage = true;
        }
        
        $user->save();
        
        $this->setSuccessMessage(__('profile.user_created_success'));
        return redirect()->route('admin.users.index');
    }
    
    #[Layout('layouts.admin', ['header' => 'Add New User'])]
    public function render()
    {
        return view('livewire.admin.users-create');
    }
} 