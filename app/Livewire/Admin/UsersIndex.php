<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

class UsersIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $role = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $userIdBeingDeleted = null;
    public $confirmingUserDeletion = false;

    protected $queryString = ['search', 'role', 'sortField', 'sortDirection'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingRole()
    {
        $this->resetPage();
    }
    
    public function updatingSortField()
    {
        $this->resetPage();
    }
    
    public function setSorting($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function mount($role = 'all')
    {
        $this->role = $role;
    }
    
    public function confirmUserDeletion($id)
    {
        $this->userIdBeingDeleted = $id;
        $this->confirmingUserDeletion = true;
    }
    
    public function deleteUser()
    {
        if (!$this->userIdBeingDeleted) {
            session()->flash('error', "Nie można usunąć użytkownika, brak identyfikatora.");
            $this->confirmingUserDeletion = false;
            return;
        }
        
        try {
            $user = User::findOrFail($this->userIdBeingDeleted);
            
            // Usuń zdjęcie użytkownika jeśli istnieje
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            
            $userName = $user->name;
            $user->delete();
            
            session()->flash('success', "Użytkownik '{$userName}' został pomyślnie usunięty.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas usuwania użytkownika: {$e->getMessage()}");
        }
        
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
    }
    
    public function cancelDeletion()
    {
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
    }
    
    #[Layout('layouts.admin', ['header' => 'Zarządzanie użytkownikami'])]
    public function render()
    {
        $query = User::query()
            ->select('users.*') // Upewnij się, że pobieramy wszystkie pola
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $search = '%' . $this->search . '%';
                    $query->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('id', 'like', $search);
                });
            })
            ->when($this->role != 'all', function ($query) {
                $query->where('role', $this->role);
            })
            ->orderBy($this->sortField, $this->sortDirection);
        
        $users = $query->paginate(10);
        
        // Upewnij się, że wszystkie potrzebne atrybuty są dostępne
        $users->each(function ($user) {
            // Pobierz URL zdjęcia profilowego
            $user->append('profile_photo_url');
        });
        
        return view('livewire.admin.users-index', [
            'users' => $users,
            'role' => $this->role
        ]);
    }
} 