<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Trainer;

/**
 * Handles the trainer account deletion process.
 * 
 * This component manages the secure deletion of trainer accounts,
 * including password verification, cleanup of associated resources,
 * and session management.
 */
class DeleteTrainerAccount extends Component
{
    public $showDeleteModal = false;
    public $password = '';
    public $errorMessage = '';
    public $debugInfo = '';
    public $passwordValidated = false;

    public function mount()
    {
        Log::info('DeleteTrainerAccount zmontowany');
    }

    public function validatePasswordAndOpenModal()
    {
        Log::info('validatePasswordAndOpenModal wywołany');
        
        if (empty($this->password)) {
            $this->errorMessage = 'Hasło jest wymagane.';
            return;
        }
        
        $this->validate([
            'password' => 'required',
        ]);

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            $this->errorMessage = 'Nie jesteś zalogowany jako trener.';
            $this->debugInfo = 'Brak trenera';
            return;
        }

        // Weryfikacja hasła
        if (!password_verify($this->password, $trainer->password)) {
            $this->errorMessage = 'Podane hasło jest nieprawidłowe.';
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

    /**
     * Process trainer account deletion after password confirmation.
     * 
     * Validates the password, cleans up associated resources like profile images,
     * logs the trainer out, invalidates the session, and permanently deletes the account.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function deleteAccount()
    {
        Log::info('deleteAccount wywołany');
        
        // Upewnij się, że hasło zostało wcześniej zweryfikowane
        if (!$this->passwordValidated) {
            $this->errorMessage = 'Najpierw należy zweryfikować hasło.';
            $this->debugInfo = 'Próba usunięcia konta bez weryfikacji hasła';
            return;
        }

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            $this->errorMessage = 'Nie jesteś zalogowany jako trener.';
            return;
        }

        // Zapamiętuję ID trenera przed wylogowaniem
        $trainerId = $trainer->id;

        // Usuwam zdjęcie profilowe jeśli istnieje (podobnie jak w User)
        if ($trainer->profile_image && $trainer->profile_image !== 'trainers/default-avatar.png') {
            Storage::disk('public')->delete($trainer->profile_image);
        }

        // Usuwam konto
        try {
            Trainer::find($trainerId)->delete();
            Log::info("Trener {$trainerId} usunięty");
            
            // Wylogowuję trenera
            Auth::guard('trainer')->logout();
            
            // Niszczę sesję
            session()->invalidate();
            session()->regenerateToken();
            
            // Przekierowuję do strony głównej z komunikatem
            return redirect()->route('home')->with('status', 'Twoje konto zostało usunięte.');
        } catch (\Exception $e) {
            Log::error("Błąd podczas usuwania trenera: " . $e->getMessage());
            $this->errorMessage = 'Wystąpił błąd podczas usuwania konta: ' . $e->getMessage();
            return;
        }
    }

    /**
     * Render the account deletion form with blog layout.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.trainer.profile.delete-trainer-account');
    }
} 