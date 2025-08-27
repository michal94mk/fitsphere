<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

class PostsIndex extends Component
{
    use WithPagination, HasFlashMessages;

    public $search = '';
    public $status = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $postIdBeingDeleted = null;
    public $confirmingPostDeletion = false;
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
            
            // Delete post image if exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
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

    #[Layout('layouts.admin', ['header' => 'Post Management'])]
    public function render()
    {
        $posts = Post::query()
            ->select('posts.*')
            ->with(['user', 'translations', 'category'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $search = '%' . $this->search . '%';
                    $query->where('title', 'like', $search)
                        ->orWhere('content', 'like', $search)
                        ->orWhere('id', 'like', $search);
                });
            })
            ->when($this->status != 'all', function ($query) {
                if ($this->status === 'published') {
                    $query->where('status', 'published');
                } elseif ($this->status === 'draft') {
                    $query->where('status', 'draft');
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        
        // Make sure all needed attributes are available
        $posts->getCollection()->transform(function ($post) {
            $post->status = $post->status ?? 'draft';
            return $post;
        });
        
        return view('livewire.admin.posts-index', [
            'posts' => $posts
        ]);
    }
} 