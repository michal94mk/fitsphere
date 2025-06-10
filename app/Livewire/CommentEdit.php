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
            if (!$this->comment->belongsToAuthUser()) {
                session()->flash('error', __('common.comment_no_permission'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }

            // Check if email is verified for regular users
            if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
                session()->flash('error', __('common.verify_email_to_comment'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }

            // Check if email is verified for trainers
            if (Auth::guard('trainer')->check() && is_null(Auth::guard('trainer')->user()->email_verified_at)) {
                session()->flash('error', __('common.verify_email_to_comment'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }
            
            $this->content = $this->comment->content;
        } catch (\Exception $e) {
            $this->logService->error('Error loading comment for edit', [
                'comment_id' => $commentId,
                'user_id' => Auth::id(),
                'trainer_id' => Auth::guard('trainer')->id(),
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
            
            if (!$this->comment->belongsToAuthUser()) {
                session()->flash('error', __('common.comment_no_permission'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }

            // Check if email is verified for regular users
            if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
                session()->flash('error', __('common.verify_email_to_comment'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }

            // Check if email is verified for trainers
            if (Auth::guard('trainer')->check() && is_null(Auth::guard('trainer')->user()->email_verified_at)) {
                session()->flash('error', __('common.verify_email_to_comment'));
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
                'trainer_id' => Auth::guard('trainer')->id(),
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