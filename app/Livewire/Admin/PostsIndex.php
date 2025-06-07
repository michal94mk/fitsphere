<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class PostsIndex extends Component
{
    use WithPagination, HasFlashMessages;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $postIdBeingDeleted = null;
    public $confirmingPostDeletion = false;

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

    public function setSorting($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmPostDeletion($id)
    {
        $this->postIdBeingDeleted = $id;
        $this->confirmingPostDeletion = true;
    }

    public function deletePost()
    {
        $this->clearMessages();
        
        if (!$this->postIdBeingDeleted) {
            $this->setErrorMessage(__('admin.post_delete_missing_id'));
            $this->confirmingPostDeletion = false;
            return;
        }
        
        try {
            $post = Post::findOrFail($this->postIdBeingDeleted);
            
            // Delete image file if exists
            if ($post->image && file_exists(storage_path('app/public/' . $post->image))) {
                unlink(storage_path('app/public/' . $post->image));
            }
            
            $postTitle = $post->title;
            $post->delete();
            
            $this->setSuccessMessage(__('admin.post_deleted', ['title' => $postTitle]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.post_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingPostDeletion = false;
        $this->postIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingPostDeletion = false;
        $this->postIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'Articles Management'])]
    public function render()
    {
        $posts = Post::query()
            ->with(['user', 'category'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('content', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('category', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.posts-index', [
            'posts' => $posts
        ]);
    }
} 