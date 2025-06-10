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

    /**
     * Real-time validation on input change
     */
    public function updated($propertyName)
    {
        // Sanitize input
        $this->sanitizeInput($propertyName);
        
        // Clear previous errors
        $this->resetErrorBag($propertyName);
        
        // Validate only the updated field (except for photo)
        if ($propertyName !== 'photo') {
            $this->validateOnly($propertyName);
        }
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
            case 'specialization':
                $this->specialization = trim(strip_tags($this->specialization));
                break;
            case 'description':
                $this->description = trim(strip_tags($this->description, '<br><p><strong><em><u>'));
                break;
            case 'biography':
                $this->biography = trim(strip_tags($this->biography, '<br><p><strong><em><u>'));
                break;
            case 'experience':
                $this->experience = (int) $this->experience;
                break;
        }
    }

    public function save()
    {
        $this->validate();
        
        try {
            $imagePath = null;
            if ($this->photo) {
                $imagePath = $this->photo->store('images/trainers', 'public');
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
                'unique:trainers,email',
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
            'specialization' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-\'\.\,\(\)\/\&À-ſ]+$/u',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]*$/u',
            ],
            'biography' => [
                'nullable',
                'string',
                'max:2000',
                'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]*$/u',
            ],
            'photo' => [
                'nullable',
                'image',
                'max:1024',
                'mimes:jpeg,jpg,png,webp',
                'dimensions:min_width=100,min_height=100,max_width=1500,max_height=1500',
            ],
            'is_approved' => [
                'boolean',
            ],
            'experience' => [
                'nullable',
                'integer',
                'min:0',
                'max:50',
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
            'specialization' => __('validation.attributes.specialization'),
            'description' => __('validation.attributes.description'),
            'biography' => __('validation.attributes.biography'),
            'photo' => __('validation.attributes.profile_photo'),
            'experience' => __('validation.attributes.experience'),
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
            
            // Specialization
            'specialization.required' => __('validation.user.specialization.required'),
            'specialization.min' => __('validation.user.specialization.min'),
            'specialization.max' => __('validation.user.specialization.max'),
            'specialization.regex' => __('validation.user.specialization.format'),
            
            // Description and Biography
            'description.max' => __('validation.trainer.description.max'),
            'description.regex' => __('validation.trainer.description.format'),
            'biography.max' => __('validation.trainer.biography.max'),
            'biography.regex' => __('validation.trainer.biography.format'),
            
            // Photo validation
            'photo.image' => __('validation.user.photo.image'),
            'photo.max' => __('validation.user.photo.max'),
            'photo.mimes' => __('validation.user.photo.mimes'),
            'photo.dimensions' => __('validation.user.photo.dimensions'),
            
            // Experience
            'experience.integer' => __('validation.trainer.experience.integer'),
            'experience.min' => __('validation.trainer.experience.min'),
            'experience.max' => __('validation.trainer.experience.max'),
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Add New Trainer'])]
    public function render()
    {
        return view('livewire.admin.trainers-create');
    }
} 