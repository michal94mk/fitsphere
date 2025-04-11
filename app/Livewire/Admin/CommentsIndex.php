<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class CommentsIndex extends Component
{
    use WithPagination;
    
    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        session()->flash('success', 'Komentarz został pomyślnie usunięty.');
    }
    
    public function render()
    {
        return view('livewire.admin.comments-index', [
            'comments' => Comment::with(['user', 'post'])->latest()->paginate(10)
        ])->layout('layouts.admin', ['header' => 'Lista komentarzy']);
    }
} 