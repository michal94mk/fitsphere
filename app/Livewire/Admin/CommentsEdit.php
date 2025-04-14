<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class CommentsEdit extends Component
{
    public $commentId;
    
    #[Rule('required|string', message: 'Treść komentarza jest wymagana.')]
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
        $this->userName = $comment->user->name ?? 'Nieznany użytkownik';
        $this->userEmail = $comment->user->email ?? 'Brak adresu email';
        $this->postTitle = $comment->post->title ?? 'Usunięty artykuł';
        $this->createdAt = $comment->created_at->format('d.m.Y H:i');
    }

    #[Layout('layouts.admin', ['header' => 'Edytuj komentarz'])]
    public function render()
    {
        return view('livewire.admin.comments-edit');
    }

    public function save()
    {
        $this->validate();
        
        try {
            $comment = Comment::findOrFail($this->commentId);
            
            $comment->content = $this->content;
            
            $comment->save();
            
            session()->flash('success', 'Komentarz został zaktualizowany!');
            return redirect()->route('admin.comments.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas aktualizacji komentarza: ' . $e->getMessage());
        }
    }
} 