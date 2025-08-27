<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CommentsEdit extends Component
{
    use HasFlashMessages;
    public $commentId;
    public $content = '';
    public $userName = '';
    public $userEmail = '';
    public $postTitle = '';
    public $createdAt = '';

    protected function rules()
    {
        return [
            'content' => 'required|string',
        ];
    }
    
    protected function messages()
    {
        return [
            'content.required' => __('validation.required', ['attribute' => 'content']),
        ];
    }

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

    public function save()
    {
        $this->validate();
        
        try {
            $comment = Comment::findOrFail($this->commentId);
            
            $comment->content = $this->content;
            
            $comment->save();
            
            $this->setSuccessMessage(__('common.comment_update_success'));
            return redirect()->route('admin.comments.index');
        } catch (\Exception $e) {
            $this->setErrorMessage(__('common.comment_update_error'));
        }
    }

    #[Layout('layouts.admin', ['header' => 'Edit Comment'])]
    public function render()
    {
        return view('livewire.admin.comments-edit');
    }
} 