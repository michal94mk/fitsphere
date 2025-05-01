<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use App\Mail\TrainerApproved;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

/**
 * Admin Trainers Index Component
 * 
 * This component manages the list of trainers in the admin panel,
 * providing filtering, sorting, approval, and deletion functionality.
 */
class TrainersIndex extends Component
{
    use WithPagination;

    /**
     * Search query string parameter
     * 
     * @var string
     */
    public $search = '';
    
    /**
     * Filter trainers by approval status
     * 
     * @var string
     */
    public $status = '';
    
    /**
     * Current field to sort results by
     * 
     * @var string
     */
    public $sortField = 'created_at';
    
    /**
     * Current sorting direction ('asc' or 'desc')
     * 
     * @var string
     */
    public $sortDirection = 'desc';
    
    /**
     * ID of trainer being deleted (for confirmation modal)
     * 
     * @var int|null
     */
    public $trainerIdBeingDeleted = null;
    
    /**
     * Flag to show/hide the deletion confirmation modal
     * 
     * @var bool
     */
    public $confirmingTrainerDeletion = false;
    
    /**
     * URL query string parameters to preserve state on page refresh
     * 
     * @var array
     */
    protected $queryString = ['search', 'status', 'sortField', 'sortDirection'];
    
    /**
     * Reset pagination when search query changes
     * 
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status filter changes
     * 
     * @return void
     */
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    /**
     * Reset pagination when sort field changes
     * 
     * @return void
     */
    public function updatingSortField()
    {
        $this->resetPage();
    }

    /**
     * Render the trainers index view with filtered and sorted results
     * 
     * @return \Illuminate\View\View
     */
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

    /**
     * Approve a trainer and send notification email
     * 
     * @param int $id The ID of the trainer to approve
     * @return void
     */
    public function approveTrainer($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Send notification email
            try {
                Mail::to($trainer->email)->send(new TrainerApproved($trainer));
                session()->flash('success', "Trainer {$trainer->name} has been approved and notification email has been sent.");
            } catch (\Exception $e) {
                session()->flash('success', "Trainer {$trainer->name} has been approved but there was an error sending the notification email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "An error occurred while approving the trainer: {$e->getMessage()}");
        }
    }

    /**
     * Change trainer status to pending (unapprove)
     * 
     * @param int $id The ID of the trainer to disapprove
     * @return void
     */
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

    /**
     * Change sort field and direction
     * 
     * @param string $field The database column to sort by
     * @return void
     */
    public function setSorting($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Show confirmation dialog before deleting a trainer
     * 
     * @param int $id The ID of the trainer to be deleted
     * @return void
     */
    public function confirmTrainerDeletion($id)
    {
        $this->trainerIdBeingDeleted = $id;
        $this->confirmingTrainerDeletion = true;
    }

    /**
     * Delete a trainer after confirmation
     * 
     * This method deletes the trainer record and their profile image
     * if one exists in storage.
     * 
     * @return void
     */
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

    /**
     * Cancel the trainer deletion process
     * 
     * @return void
     */
    public function cancelDeletion()
    {
        $this->confirmingTrainerDeletion = false;
        $this->trainerIdBeingDeleted = null;
    }
}