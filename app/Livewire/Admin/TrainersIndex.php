<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\EmailService;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class TrainersIndex extends Component
{
    use WithPagination, HasFlashMessages;

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
        $this->clearMessages();
        
        try {
            $trainer = User::where('role', 'trainer')->findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Send notification email
            try {
                $emailService = new EmailService();
                $emailService->sendTrainerApprovedEmail($trainer);
                $this->setSuccessMessage(__('admin.trainer_approved_with_email', ['name' => $trainer->name]));
            } catch (\Exception $e) {
                $this->setSuccessMessage(__('admin.trainer_approved_no_email', ['name' => $trainer->name, 'error' => $e->getMessage()]));
            }
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_approve_error', ['error' => $e->getMessage()]));
        }
    }

    public function disapproveTrainer($id)
    {
        $this->clearMessages();
        
        try {
            $trainer = User::where('role', 'trainer')->findOrFail($id);
            $trainer->is_approved = false;
            $trainer->save();
            
            $this->setSuccessMessage(__('admin.trainer_disapproved', ['name' => $trainer->name]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_disapprove_error', ['error' => $e->getMessage()]));
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
        $this->clearMessages();
        
        if (!$this->trainerIdBeingDeleted) {
            $this->setErrorMessage(__('admin.trainer_delete_missing_id'));
            $this->confirmingTrainerDeletion = false;
            return;
        }
        
        try {
            $trainer = User::where('role', 'trainer')->findOrFail($this->trainerIdBeingDeleted);
            
            // Delete profile image if it exists
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

    #[Layout('layouts.admin', ['header' => 'Trainer Management'])]
    public function render()
    {
        $trainers = User::where('role', 'trainer')
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