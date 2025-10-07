<?php

namespace App\Livewire\Admin;

use App\Models\Subscriber;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class SubscribersIndex extends Component
{
    use WithPagination, HasFlashMessages;
    
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $subscriberIdBeingDeleted = null;
    public $confirmingSubscriberDeletion = false;
    public $page = 1;

    protected $queryString = ['search', 'sortField', 'sortDirection', 'page'];
    
    public function updatingSearch()
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
    
    public function confirmSubscriberDeletion($id)
    {
        $this->subscriberIdBeingDeleted = $id;
        $this->confirmingSubscriberDeletion = true;
    }
    
    public function deleteSubscriber()
    {
        $this->clearMessages();
        
        if (!$this->subscriberIdBeingDeleted) {
            $this->setErrorMessage(__('admin.subscriber_delete_missing_id'));
            $this->confirmingSubscriberDeletion = false;
            return;
        }
        
        try {
            $subscriber = Subscriber::findOrFail($this->subscriberIdBeingDeleted);
            $subscriberEmail = $subscriber->email;
            $subscriber->delete();
            
            $this->setSuccessMessage(__('admin.subscriber_deleted', ['email' => $subscriberEmail]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.subscriber_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingSubscriberDeletion = false;
        $this->subscriberIdBeingDeleted = null;
    }
    
    public function cancelDeletion()
    {
        $this->confirmingSubscriberDeletion = false;
        $this->subscriberIdBeingDeleted = null;
    }
    
    #[Layout('layouts.admin', ['header' => 'admin.subscriber_management'])]
    public function render()
    {
        $subscribers = Subscriber::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $search = '%' . $this->search . '%';
                    $query->where('email', 'like', $search)
                        ->orWhere('id', 'like', $search);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
        
        return view('livewire.admin.subscribers-index', [
            'subscribers' => $subscribers
        ]);
    }
}
