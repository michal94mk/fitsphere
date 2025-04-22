<?php

namespace App\Livewire\User\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

/**
 * Handles the user account deletion process.
 * 
 * This component manages the secure deletion of user accounts,
 * including password verification, cleanup of associated resources,
 * and session management.
 */
class DeleteUserAccount extends Component
{
    public $showDeleteModal = false;
    public $password = '';
    public $errorMessage = '';
    public $debugInfo = '';
    public $passwordValidated = false;

    public function mount()
    {
        // Debug info removed as requested
    }

    public function validatePasswordAndOpenModal()
    {
        Log::info('validatePasswordAndOpenModal wywołany');
        
        if (empty($this->password)) {
            $this->errorMessage = __('profile.password_required');
            return;
        }
        
        $this->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->password, $user->password)) {
            $this->errorMessage = __('profile.password_incorrect');
            $this->debugInfo = 'Nieprawidłowe hasło';
            return;
        }

        // Hasło zostało zweryfikowane, możemy otworzyć modal
        $this->passwordValidated = true;
        $this->errorMessage = '';
        $this->showDeleteModal = true;
        $this->debugInfo = 'Hasło zweryfikowane, modal otwarty';
    }

    public function openModal()
    {
        Log::info('openModal wywołany');
        $this->reset(['errorMessage']);
        $this->showDeleteModal = true;
        $this->debugInfo = 'Modal otwarty';
    }

    public function closeModal()
    {
        Log::info('closeModal wywołany');
        $this->showDeleteModal = false;
        $this->debugInfo = 'Modal zamknięty';
    }

    public function deleteAccount()
    {
        Log::info('deleteAccount wywołany');
        
        // Upewnij się, że hasło zostało wcześniej zweryfikowane
        if (!$this->passwordValidated) {
            $this->errorMessage = __('profile.verify_first');
            $this->debugInfo = 'Próba usunięcia konta bez weryfikacji hasła';
            return;
        }

        $user = Auth::user();
        $this->debugInfo = 'Potwierdzono usunięcie konta';

        // Usuń pliki użytkownika (jeśli istnieją)
        if ($user->profile_image && $user->profile_image !== 'users/default-avatar.png') {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Zapisz ID użytkownika
        $userId = $user->id;

        // Usuwanie użytkownika - użyj find i delete dla pewności
        User::find($userId)->delete();

        // Wylogowanie
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Przekierowanie na stronę główną
        return redirect()->route('home')->with('status', __('profile.account_deleted'));
    }

    /**
     * Render the account deletion form.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.user.profile.delete-user-account');
    }
} 