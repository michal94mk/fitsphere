<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

class TrainersIndex extends Component
{
    use WithPagination, HasFlashMessages;
    
    public $search = '';
    public $status = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $trainerIdBeingDeleted = null;
    public $confirmingTrainerDeletion = false;
    public $trainerIdBeingApproved = null;
    public $confirmingTrainerApproval = false;
    public $page = 1;

    protected $queryString = ['search', 'status', 'sortField', 'sortDirection', 'page'];
    
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
    
    public function sortBy($field)
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
        $this->clearMessages();
        
        if (!$this->trainerIdBeingDeleted) {
            $this->setErrorMessage(__('admin.trainer_delete_missing_id'));
            $this->confirmingTrainerDeletion = false;
            return;
        }
        
        try {
            $trainer = User::findOrFail($this->trainerIdBeingDeleted);
            
            // Delete trainer image if exists
            if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                Storage::disk('public')->delete($trainer->image);
            }
            
            $trainerName = $trainer->name;
            $trainer->delete();
            
            $this->setSuccessMessage(__('admin.trainer_deleted', ['name' => $trainerName]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }
    
    public function cancelDeletion()
    {
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }
    
    public function confirmTrainerApproval($id)
    {
        $this->trainerIdBeingApproved = $id;
        $this->confirmingTrainerApproval = true;
    }
    
    public function approveTrainer()
    {
        $this->clearMessages();
        
        if (!$this->trainerIdBeingApproved) {
            $this->setErrorMessage(__('admin.trainer_approve_missing_id'));
            $this->confirmingTrainerApproval = false;
            return;
        }
        
        try {
            $trainer = User::findOrFail($this->trainerIdBeingApproved);
            $trainer->is_approved = true;
            $trainer->save();
            
            $this->setSuccessMessage(__('admin.trainer_approved', ['name' => $trainer->name]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_approve_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingTrainerApproval = false;
        $this->trainerIdBeingApproved = null;
    }
    
    public function cancelApproval()
    {
        $this->confirmingTrainerApproval = false;
        $this->trainerIdBeingApproved = null;
    }
    
    #[Layout('layouts.admin', ['header' => 'admin.trainer_management'])]
    public function render()
    {
        $trainers = User::query()
            ->select('users.*')
            ->where('role', 'like', '%trainer%')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $search = '%' . $this->search . '%';
                    $query->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('specialization', 'like', $search)
                        ->orWhere('id', 'like', $search);
                });
            })
            ->when($this->status != 'all', function ($query) {
                if ($this->status === 'approved') {
                    $query->where('is_approved', true);
                } elseif ($this->status === 'pending') {
                    $query->where('is_approved', false);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        
        // Make sure all needed attributes are available
        $trainers->getCollection()->transform(function ($trainer) {
            $trainer->role = $trainer->role ?? 'trainer';
            $trainer->is_approved = $trainer->is_approved ?? false;
            $trainer->specialization = $trainer->specialization ?? null;
            return $trainer;
        });
        
        return view('livewire.admin.trainers-index', [
            'trainers' => $trainers
        ]);
    }
}