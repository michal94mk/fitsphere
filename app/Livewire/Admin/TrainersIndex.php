<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use App\Mail\TrainerApproved;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class TrainersIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $trainerIdBeingDeleted = null;
    public $confirmingTrainerDeletion = false;
    
    protected $queryString = ['search', 'status', 'sortField', 'sortDirection'];
    
    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function updatingSortField()
    {
        $this->resetPage();
    }

    #[Layout('layouts.admin', ['header' => 'Zarządzanie trenerami'])]
    public function render()
    {
        $trainers = Trainer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('specialization', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status === 'approved', function ($query) {
                $query->where('is_approved', true);
            })
            ->when($this->status === 'pending', function ($query) {
                $query->where('is_approved', false);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        
        return view('livewire.admin.trainers-index', [
            'trainers' => $trainers
        ]);
    }

    public function approveTrainer($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Wysyłka emaila z powiadomieniem
            try {
                Mail::to($trainer->email)->send(new TrainerApproved($trainer));
                session()->flash('success', "Trener {$trainer->name} został zatwierdzony, a powiadomienie email zostało wysłane.");
            } catch (\Exception $e) {
                session()->flash('success', "Trener {$trainer->name} został zatwierdzony, ale wystąpił błąd podczas wysyłania powiadomienia email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zatwierdzania trenera: {$e->getMessage()}");
        }
    }

    public function disapproveTrainer($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->is_approved = false;
            $trainer->save();
            
            session()->flash('success', "Status trenera {$trainer->name} został zmieniony na oczekujący.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zmiany statusu trenera: {$e->getMessage()}");
        }
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

    public function confirmTrainerDeletion($id)
    {
        $this->trainerIdBeingDeleted = $id;
        $this->confirmingTrainerDeletion = true;
    }

    public function deleteTrainer()
    {
        if (!$this->trainerIdBeingDeleted) {
            session()->flash('error', "Nie można usunąć trenera, brak identyfikatora.");
            $this->confirmingTrainerDeletion = false;
            return;
        }
        
        try {
            $trainer = Trainer::findOrFail($this->trainerIdBeingDeleted);
            
            // Usuń zdjęcie jeśli istnieje
            if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                Storage::disk('public')->delete($trainer->image);
            }
            
            $trainerName = $trainer->name;
            $trainer->delete();
            
            session()->flash('success', "Trener {$trainerName} został usunięty.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas usuwania trenera: {$e->getMessage()}");
        }
        
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }
}