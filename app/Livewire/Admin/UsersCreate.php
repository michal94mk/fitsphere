<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Trainer;
use Livewire\Attributes\Layout;

class UsersCreate extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $image;
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|regex:/^[\pL\s\-\']+$/u',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:admin,user',
            'image' => 'nullable|image|max:1024'
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
            'role.required' => __('validation.user.role.required'),
            'role.in' => __('validation.user.role.in'),
            'image.image' => __('validation.user.image.image'),
            'image.max' => __('validation.user.image.max', ['max' => 1024]),
        ];
    }
    
    public function store()
    {
        $this->validate();
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->role = $this->role;
        
        if ($this->image) {
            $imagePath = $this->image->store('users', 'public');
            $user->image = $imagePath;
        }
        
        $user->save();
        
        session()->flash('success', __('profile.user_created_success'));
        return redirect()->route('admin.users.index');
    }
    
    #[Layout('layouts.admin', ['header' => 'Add New User'])]
    public function render()
    {
        return view('livewire.admin.users-create');
    }
} 