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
    public $page = 1;
    protected $queryString = ['search', 'status', 'sortField', 'sortDirection', 'page'];
    
    public function updatingSearch()
    {
        $this->resetPage();
        $this->clearCache();
    }

    public function updatingStatus()
    {
        $this->resetPage();
        $this->clearCache();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->clearCache();
    }

    public function updatingPage()
    {
        $this->clearCache();
    }

    protected function clearCache()
    {
        $cacheKey = 'admin.trainers.' . $this->search . '.' . $this->status . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        cache()->forget($cacheKey);
    }

    public function approveTrainer($id)
    {
        $this->clearMessages();
        
        try {
            $trainer = User::where('role', 'trainer')->findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Clear cache to refresh the list
            $this->clearCache();
            
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
            
            // Clear cache to refresh the list
            $this->clearCache();
            
            $this->setSuccessMessage(__('admin.trainer_disapproved', ['name' => $trainer->name]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_disapprove_error', ['error' => $e->getMessage()]));
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
        $cacheKey = 'admin.trainers.' . $this->search . '.' . $this->status . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        
        $trainers = cache()->remember($cacheKey, 300, function () {
            return User::query()
                ->where('role', 'trainer')
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status, function ($query) {
                    $query->where('is_approved', $this->status === 'approved');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);
        });

        return view('livewire.admin.trainers-index', [
            'trainers' => $trainers
        ]);
    }
}