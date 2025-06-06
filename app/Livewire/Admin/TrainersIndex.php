<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use App\Services\EmailService;
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

    public function approveTrainer($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Send notification email
            try {
                $emailService = new EmailService();
                $emailService->sendTrainerApprovedEmail($trainer);
                session()->flash('success', "Trainer {$trainer->name} has been approved and notification email has been sent.");
            } catch (\Exception $e) {
                session()->flash('success', "Trainer {$trainer->name} has been approved but there was an error sending the notification email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "An error occurred while approving the trainer: {$e->getMessage()}");
        }
    }

    public function disapproveTrainer($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->is_approved = false;
            $trainer->save();
            
            session()->flash('success', "Trainer {$trainer->name}'s status has been changed to pending.");
        } catch (\Exception $e) {
            session()->flash('error', "An error occurred while changing the trainer's status: {$e->getMessage()}");
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
            session()->flash('error', "Cannot delete trainer, missing identifier.");
            $this->confirmingTrainerDeletion = false;
            return;
        }
        
        try {
            $trainer = Trainer::findOrFail($this->trainerIdBeingDeleted);
            
            // Delete profile image if it exists
            if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                Storage::disk('public')->delete($trainer->image);
            }
            
            $trainerName = $trainer->name;
            $trainer->delete();
            
            session()->flash('success', "Trainer {$trainerName} has been deleted.");
        } catch (\Exception $e) {
            session()->flash('error', "An error occurred while deleting the trainer: {$e->getMessage()}");
        }
        
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'Trainer Management'])]
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
}