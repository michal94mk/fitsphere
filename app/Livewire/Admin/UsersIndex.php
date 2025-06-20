<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

class UsersIndex extends Component
{
    use WithPagination, HasFlashMessages;
    
    public $search = '';
    public $role = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $userIdBeingDeleted = null;
    public $confirmingUserDeletion = false;
    public $page = 1;

    protected $queryString = ['search', 'role', 'sortField', 'sortDirection', 'page'];
    
    public function updatingSearch()
    {
        $this->resetPage();
        $this->clearCache();
    }
    
    public function updatingRole()
    {
        $this->resetPage();
        $this->clearCache();
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
        $this->clearCache();
    }
    
    public function updatingPage()
    {
        $this->clearCache();
    }
    
    protected function clearCache()
    {
        $cacheKey = 'admin.users.' . $this->search . '.' . $this->role . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        cache()->forget($cacheKey);
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
        $this->clearMessages();
        
        if (!$this->userIdBeingDeleted) {
            $this->setErrorMessage(__('admin.user_delete_missing_id'));
            $this->confirmingUserDeletion = false;
            return;
        }
        
        try {
            $user = User::findOrFail($this->userIdBeingDeleted);
            
            // Delete user image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            
            $userName = $user->name;
            $user->delete();
            
            // Clear cache to refresh the list
            $this->clearCache();
            
            $this->setSuccessMessage(__('admin.user_deleted', ['name' => $userName]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.user_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
    }
    
    public function cancelDeletion()
    {
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
    }
    
    #[Layout('layouts.admin', ['header' => 'User Management'])]
    public function render()
    {
        $cacheKey = 'admin.users.' . $this->search . '.' . $this->role . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        
        $users = cache()->remember($cacheKey, 300, function () {
            $query = User::query()
                ->select('users.*') // Make sure we select all fields
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

            return $query->paginate(10);
        });
        
        // Make sure all needed attributes are available
        $users->getCollection()->transform(function ($user) {
            $user->role = $user->role ?? 'user';
            $user->is_approved = $user->is_approved ?? false;
            $user->specialization = $user->specialization ?? null;
            return $user;
        });
        
        return view('livewire.admin.users-index', [
            'users' => $users
        ]);
    }
} 