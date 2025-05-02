<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

/**
 * Admin comment editing component
 */
class CommentsEdit extends Component
{
    public $commentId;
    
    #[Rule('required|string', message: 'Comment content is required.')]
    public $content = '';

    public $userName = '';
    public $userEmail = '';
    public $postTitle = '';
    public $createdAt = '';

    public function mount($id)
    {
        $this->commentId = $id;
        $comment = Comment::with(['user', 'post'])->findOrFail($id);
        
        $this->content = $comment->content;
        $this->userName = $comment->user->name ?? 'Unknown user';
        $this->userEmail = $comment->user->email ?? 'No email address';
        $this->postTitle = $comment->post->title ?? 'Deleted post';
        $this->createdAt = $comment->created_at->format('d.m.Y H:i');
    }

    #[Layout('layouts.admin', ['header' => 'Edit Comment'])]
    public function render()
    {
        return view('livewire.admin.comments-edit');
    }

    /**
     * Save the edited comment
     */
    public function save()
    {
        $this->validate();
        
        try {
            $comment = Comment::findOrFail($this->commentId);
            
            $comment->content = $this->content;
            
            $comment->save();
            
            session()->flash('success', 'Comment has been updated!');
            return redirect()->route('admin.comments.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the comment: ' . $e->getMessage());
        }
    }
} 