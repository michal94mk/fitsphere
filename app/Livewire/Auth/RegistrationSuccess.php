<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Auth\Events\Registered;

class RegistrationSuccess extends Component
{
    public $userType;
    public $email;
    public $resent = false;
    
    public function mount($userType = null)
    {
        $this->userType = $userType ?? session('user_type', 'user');
        $this->email = session('email', '');
    }
    
    public function resendVerificationEmail()
    {
        if (empty($this->email)) {
            session()->flash('error', 'Adres email nie jest dostępny. Spróbuj zalogować się lub zarejestrować ponownie.');
            return;
        }
        
        // Znajdź użytkownika lub trenera na podstawie adresu email
        if ($this->userType == 'trainer') {
            $user = Trainer::where('email', $this->email)->first();
        } else {
            $user = User::where('email', $this->email)->first();
        }
        
        if (!$user) {
            session()->flash('error', 'Nie znaleziono konta z podanym adresem email.');
            return;
        }
        
        if ($user->hasVerifiedEmail()) {
            session()->flash('info', 'Ten adres email został już zweryfikowany. Możesz się zalogować.');
            return;
        }
        
        // Wyślij ponownie email weryfikacyjny bezpośrednio, bez wywoływania zdarzenia
        // event(new Registered($user));
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }
        
        $this->resent = true;
        session()->flash('success', 'Link weryfikacyjny został wysłany ponownie na adres: ' . $this->email);
    }
    
    #[Layout('layouts.blog', ['title' => 'Rejestracja zakończona pomyślnie'])]
    public function render()
    {
        return view('livewire.auth.registration-success');
    }
}
