<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;


class UpdateTrainerPassword extends Component
{
    public $current_password, $new_password, $new_password_confirmation;
    public $user = null;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ];

    public function mount()
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
        
        $this->user = Auth::guard('trainer')->user();
    }

    protected function messages()
    {
        return [
            'current_password.required' => __('validation.password.current_required'),
            'new_password.required' => __('validation.password.new_required'),
            'new_password.min' => __('validation.password.min', ['min' => 8]),
            'new_password.confirmed' => __('validation.password.confirmed'),
        ];
    }

    public function updatePassword()
    {
        $this->validate();

        if (!$this->user) {
            session()->flash('error', __('profile.not_logged_in'));
            return;
        }

        if (!Hash::check($this->current_password, $this->user->password)) {
            session()->flash('error', __('profile.current_password_incorrect'));
            return;
        }

        $this->user->update(['password' => Hash::make($this->new_password)]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('status', __('profile.password_updated'));
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer.profile.update-trainer-password');
    }
} 