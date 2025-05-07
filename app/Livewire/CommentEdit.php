<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Services\LogService;

class CommentEdit extends Component
{
    public $comment;
    public $content;
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }
    
    public function mount($commentId)
    {
        try {
            $this->comment = Comment::findOrFail($commentId);
            
            // Check if user has permission to edit this comment
            if ($this->comment->user_id !== Auth::id()) {
                session()->flash('error', __('common.comment_no_permission'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }
            
            $this->content = $this->comment->content;
        } catch (\Exception $e) {
            $this->logService->error('Error loading comment for edit', [
                'comment_id' => $commentId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.comment_not_found'));
            return redirect()->route('home');
        }
    }
    
    public function update()
    {
        try {
            $this->validate([
                'content' => 'required|min:3|max:500'
            ]);
            
            if ($this->comment->user_id !== Auth::id()) {
                session()->flash('error', __('common.comment_no_permission'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }
            
            $this->comment->update([
                'content' => $this->content
            ]);
            
            session()->flash('success', __('common.comment_update_success'));
            return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
        } catch (\Exception $e) {
            $this->logService->error('Error updating comment', [
                'comment_id' => $this->comment->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.comment_update_error'));
            return null;
        }
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