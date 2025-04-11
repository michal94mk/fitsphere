<?php

namespace App\Livewire;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Auth\Events\Registered;

class BecomeTrainer extends Component
{
    use WithFileUploads;
    
    #[Rule('required|string|max:255', message: 'Imię i nazwisko są wymagane.')]
    public $name = '';
    
    #[Rule('required|string|email|max:255|unique:trainers', message: 'Ten adres email jest już zajęty lub nieprawidłowy.')]
    public $email = '';
    
    #[Rule('required|string|min:8|confirmed', message: 'Hasło musi mieć co najmniej 8 znaków.')]
    public $password = '';
    
    public $password_confirmation = '';
    
    #[Rule('required|string|max:255', message: 'Specjalizacja jest wymagana.')]
    public $specialization = '';
    
    #[Rule('nullable|string')]
    public $description = '';
    
    #[Rule('nullable|string')]
    public $biography = '';
    
    #[Rule('nullable|integer|min:0|max:100', message: 'Doświadczenie musi być liczbą od 0 do 100.')]
    public $experience = 0;
    
    #[Rule('nullable|image|max:1024', message: 'Zdjęcie musi być obrazem o maksymalnym rozmiarze 1MB.')]
    public $image;
    
    // Zmienne lokalne dla komunikatów
    public $success = false;
    public $verification_message = false;
    
    public function save()
    {
        $this->validate();
        
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('trainers', 'public');
        }
        
        $trainer = Trainer::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'specialization' => $this->specialization,
            'description' => $this->description,
            'bio' => $this->biography,
            'experience' => $this->experience,
            'image' => $imagePath,
            'is_approved' => false,
        ]);
        
        // Wywołanie zdarzenia zarejestrowania użytkownika, które wyzwoli wysłanie e-maila weryfikacyjnego
        event(new Registered($trainer));
        
        // Reset formularza
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'specialization', 'description', 'biography', 'experience', 'image']);
        
        // Ustaw zmienne komunikatu bezpośrednio w komponencie
        $this->success = true;
        $this->verification_message = true;
    }
    
    #[Layout('layouts.blog', ['title' => 'Zostań naszym trenerem'])]
    public function render()
    {
        return view('livewire.become-trainer');
    }
} 