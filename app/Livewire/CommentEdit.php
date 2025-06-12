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
            
            if (!$this->comment->belongsToAuthUser()) {
                session()->flash('error', __('common.comment_no_permission'));
                return redirect()->route('post.show', ['postId' => $this->comment->post_id]);
            }

            // Check if email is verified for regular users
            if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
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
    
    public function saveComment()
    {
        if (!Auth::check()) {
            session()->flash('error', __('common.login_required'));
            return;
        }

        $user = Auth::user();
        
        if (is_null($user->email_verified_at)) {
            session()->flash('error', __('common.email_not_verified'));
            return;
        }

        $this->validate([
            'content' => 'required|string|max:500',
        ]);

        $updateData = [
            'content' => $this->content,
        ];

        // Set appropriate relationship
        $roles = explode(',', $user->role);
        if (in_array('trainer', $roles)) {
            $updateData['trainer_id'] = $user->id;
        } else {
            $updateData['user_id'] = $user->id;
        }

        $this->comment->update($updateData);

        session()->flash('success', __('common.comment_updated'));
        $this->dispatch('comment-updated');
    }

    public function deleteComment()
    {
        if (!Auth::check()) {
            session()->flash('error', __('common.login_required'));
            return;
        }

        $user = Auth::user();
        
        if (is_null($user->email_verified_at)) {
            session()->flash('error', __('common.email_not_verified'));
            return;
        }

        // Check ownership
        if (($this->comment->user_id && $this->comment->user_id !== $user->id) ||
            ($this->comment->trainer_id && $this->comment->trainer_id !== $user->id)) {
            session()->flash('error', __('common.unauthorized_delete'));
            return;
        }

        $deleteData = [
            'deleted_at' => now(),
        ];

        // Set appropriate relationship  
        $roles = explode(',', $user->role);
        if (in_array('trainer', $roles)) {
            $deleteData['trainer_id'] = $user->id;
        } else {
            $deleteData['user_id'] = $user->id;
        }

        $this->comment->update($deleteData);

        session()->flash('success', __('common.comment_deleted'));
        $this->dispatch('comment-deleted');
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.comment-edit');
    }
}