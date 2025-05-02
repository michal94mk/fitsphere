<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

/**
 * Handles comment editing functionality
 */
class CommentEdit extends Component
{
    public $comment;
    public $content;
    
    public function mount($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);
        
        // Check if user has permission to edit this comment
        if ($this->comment->user_id !== Auth::id()) {
            session()->flash('error', 'You do not have permission to edit this comment.');
            return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
        }
        
        $this->content = $this->comment->content;
    }
    
    /**
     * Update the comment content
     */
    public function update()
    {
        $this->validate([
            'content' => 'required|min:3|max:500'
        ]);
        
        if ($this->comment->user_id !== Auth::id()) {
            session()->flash('error', 'You do not have permission to edit this comment.');
            return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
        }
        
        $this->comment->update([
            'content' => $this->content
        ]);
        
        session()->flash('success', 'Comment has been updated.');
        return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
    }
    
    public function cancel()
    {
        return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.comment-edit');
    }
}
