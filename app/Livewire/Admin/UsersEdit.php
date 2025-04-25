<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule as FormRule;

class UsersEdit extends Component
{
    use WithFileUploads;
    
    public $userId;
    
    #[FormRule('required|string|max:255', message: 'Imię jest wymagane.')]
    public $name = '';
    
    public $email = '';
    
    public $password = '';
    
    public $password_confirmation = '';
    
    #[FormRule('required|string|in:admin,user', message: 'Rola jest wymagana.')]
    public $role = '';
    
    #[FormRule('nullable|image|max:1024', message: 'Zdjęcie musi być obrazem o maksymalnym rozmiarze 1MB.')]
    public $photo = null;
    
    public $currentImage = '';
    public $existing_photo = null;
    public $changePassword = false;
    
    public function mount($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($id);
        
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->currentImage = $user->image;
        
        // Ustaw existing_photo na podstawie currentImage
        if ($this->currentImage) {
            $this->existing_photo = asset('storage/' . $this->currentImage);
        }
    }
    
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'password' => $this->changePassword ? 'required|string|min:8|confirmed' : 'nullable',
        ];
    }
    
    public function messages()
    {
        return [
            'email.required' => 'Adres email jest wymagany.',
            'email.email' => 'Wprowadź poprawny adres email.',
            'email.unique' => 'Ten adres email jest już zajęty.',
            'password.required' => 'Hasło jest wymagane gdy zmieniasz hasło.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Potwierdzenie hasła nie zgadza się.',
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Edytuj użytkownika'])]
    public function render()
    {
        return view('livewire.admin.users-edit');
    }

    public function save()
    {
        // Walidacja podstawowa
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|in:admin,user',
        ]);
        
        // Walidacja hasła tylko gdy włączono zmianę hasła
        if ($this->changePassword) {
            $this->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
        }
        
        try {
            $user = User::findOrFail($this->userId);
            
            $imagePath = $user->image;
            if ($this->photo) {
                // Remove old image if exists
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    // Delete the old image file
                    Storage::disk('public')->delete($user->image);
                }
                $imagePath = $this->photo->store('users', 'public');
            }
            
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;
            
            if ($this->changePassword && $this->password) {
                $user->password = Hash::make($this->password);
            }
            
            if ($imagePath) {
                $user->image = $imagePath;
            }
            
            $user->save();
            
            session()->flash('success', 'Dane użytkownika zostały zaktualizowane!');
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas aktualizacji użytkownika: ' . $e->getMessage());
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    public function removePhoto()
    {
        if ($this->currentImage) {
            try {
                // Delete the old image file from storage if it exists
                if (Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                
                // Update the database to remove the image reference
                $user = User::findOrFail($this->userId);
                $user->image = null;
                $user->save();
                
                // Update local properties
                $this->currentImage = null;
                $this->existing_photo = null;
                
                // Reset the new photo too if it exists
                $this->photo = null;
                
                session()->flash('success', 'Zdjęcie zostało usunięte.');
            } catch (\Exception $e) {
                session()->flash('error', 'Nie udało się usunąć zdjęcia: ' . $e->getMessage());
            }
        }
    }
} 