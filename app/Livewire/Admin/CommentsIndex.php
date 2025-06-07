<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class CommentsIndex extends Component
{
    use WithPagination, HasFlashMessages;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $commentIdBeingDeleted = null;
    public $confirmingCommentDeletion = false;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
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

    public function confirmCommentDeletion($id)
    {
        $this->commentIdBeingDeleted = $id;
        $this->confirmingCommentDeletion = true;
    }

    public function deleteComment()
    {
        $this->clearMessages();
        
        if (!$this->commentIdBeingDeleted) {
            $this->setErrorMessage(__('admin.cannot_delete_comment_missing_id'));
            $this->confirmingCommentDeletion = false;
            return;
        }
        
        try {
            $comment = Comment::findOrFail($this->commentIdBeingDeleted);
            $comment->delete();
            
            $this->setSuccessMessage(__('admin.comment_deleted'));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.comment_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingCommentDeletion = false;
        $this->commentIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingCommentDeletion = false;
        $this->commentIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'Comments Management'])]
    public function render()
    {
        $comments = Comment::with(['user', 'post'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('content', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('post', function ($query) {
                            $query->where('title', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.comments-index', compact('comments'));
    }
} 