<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class TrainersCreate extends Component
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
    
    #[Rule('nullable|image|max:1024', message: 'Zdjęcie musi być obrazem o maksymalnym rozmiarze 1MB.')]
    public $photo = null;
    
    #[Rule('boolean')]
    public $is_approved = false;
    
    #[Rule('nullable|integer|min:0|max:100')]
    public $experience = 0;

    // Define validation rules matching Trainer model's fillable attributes
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:trainers',
        'password' => 'required|string|min:8|confirmed',
        'specialization' => 'required|string|max:255',
        'description' => 'nullable|string',
        'biography' => 'nullable|string',
        'photo' => 'nullable|image|max:1024', // 1MB Max
        'is_approved' => 'boolean',
        'experience' => 'nullable|integer|min:0|max:100',
    ];

    protected $messages = [
        'name.required' => 'Imię i nazwisko są wymagane.',
        'email.required' => 'Adres email jest wymagany.',
        'email.email' => 'Wprowadź poprawny adres email.',
        'email.unique' => 'Ten adres email jest już zajęty.',
        'password.required' => 'Hasło jest wymagane.',
        'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
        'password.confirmed' => 'Potwierdzenie hasła nie zgadza się.',
        'specialization.required' => 'Specjalizacja jest wymagana.',
        'photo.image' => 'Wybrany plik musi być obrazem.',
        'photo.max' => 'Zdjęcie nie może być większe niż 1MB.',
    ];

    #[Layout('layouts.admin', ['header' => 'Dodaj nowego trenera'])]
    public function render()
    {
        return view('livewire.admin.trainers-create');
    }

    public function save()
    {
        $this->validate();
        
        try {
            $imagePath = null;
            if ($this->photo) {
                $imagePath = $this->photo->store('trainers', 'public');
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
                ? 'Trener został pomyślnie dodany i zatwierdzony!' 
                : 'Trener został pomyślnie dodany! Musi zweryfikować swój adres email, aby aktywować konto.';
            
            session()->flash('success', $successMessage);
            return redirect()->route('admin.trainers.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas dodawania trenera: ' . $e->getMessage());
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }
} 