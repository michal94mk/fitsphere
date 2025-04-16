<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Trainer;

class VerifyEmailHandler extends Component
{
    public $id;
    public $hash;
    public $message;
    public $error = false;

    public function mount($id, $hash)
    {
        $this->id = $id;
        $this->hash = $hash;
        
        try {
            // Próbujemy znaleźć zarówno użytkownika jak i trenera
            $userModel = null;
            $trainerModel = null;
            $isTrainer = false;
            $validatedUser = null;
            
            try {
                // Próba znalezienia użytkownika
                $userModel = User::find($id);
            } catch (\Exception $e) {
                // Ignorujemy błędy
            }
            
            try {
                // Próba znalezienia trenera
                $trainerModel = Trainer::find($id);
            } catch (\Exception $e) {
                // Ignorujemy błędy
            }
            
            // Jeśli nie znaleziono ani użytkownika ani trenera, zwróć błąd
            if (!$userModel && !$trainerModel) {
                throw new \Exception('Nie znaleziono użytkownika o podanym ID.');
            }
            
            // Sprawdź, który model pasuje do hasha (może być tylko jeden prawidłowy)
            if ($userModel && hash_equals(sha1($userModel->getEmailForVerification()), (string) $hash)) {
                $validatedUser = $userModel;
                $isTrainer = false;
            } elseif ($trainerModel && hash_equals(sha1($trainerModel->getEmailForVerification()), (string) $hash)) {
                $validatedUser = $trainerModel;
                $isTrainer = true;
            } else {
                throw new \Exception('Nieprawidłowy hash weryfikacyjny dla podanego użytkownika.');
            }
            
            // Sprawdź, czy email jest już zweryfikowany
            if ($validatedUser->hasVerifiedEmail()) {
                $this->message = 'Twój adres email został już wcześniej zweryfikowany!';
                return redirect('/login')->with('verified', $this->message);
            }

            // Oznacz email jako zweryfikowany i zapisz zmiany
            $validatedUser->markEmailAsVerified();
            
            // Uruchom zdarzenie Verified
            event(new Verified($validatedUser));
            
            // Komunikat o sukcesie i przekierowanie
            $this->message = 'Twój adres email został pomyślnie zweryfikowany!';
            
            if ($isTrainer) {
                $this->message .= ' Administrator wkrótce sprawdzi Twoje zgłoszenie.';
            }
            
            // Przekierowanie do logowania
            return redirect('/login')->with('verified', $this->message);
            
        } catch (\Exception $e) {
            // W przypadku błędu, pokazujemy komunikat i przekierowujemy do logowania
            $this->error = true;
            $this->message = 'Wystąpił błąd podczas weryfikacji: ' . $e->getMessage();
            return redirect('/login')->with('error', $this->message);
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email-handler');
    }
}
