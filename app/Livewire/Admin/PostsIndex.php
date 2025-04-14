<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class PostsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $postIdBeingDeleted = null;
    public $confirmingPostDeletion = false;

    protected $queryString = ['search', 'status', 'sortField', 'sortDirection'];

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

    public function setSorting($field)
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
        if (!$this->postIdBeingDeleted) {
            session()->flash('error', "Nie można usunąć artykułu, brak identyfikatora.");
            $this->confirmingPostDeletion = false;
            return;
        }
        
        try {
            $post = Post::findOrFail($this->postIdBeingDeleted);
            
            // Usuń plik obrazka jeśli istnieje
            if ($post->image && file_exists(storage_path('app/public/' . $post->image))) {
                unlink(storage_path('app/public/' . $post->image));
            }
            
            $postTitle = $post->title;
            $post->delete();
            
            session()->flash('success', "Artykuł '{$postTitle}' został pomyślnie usunięty.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas usuwania artykułu: {$e->getMessage()}");
        }
        
        $this->confirmingPostDeletion = false;
        $this->postIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingPostDeletion = false;
        $this->postIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'Zarządzanie artykułami'])]
    public function render()
    {
        $posts = Post::query()
            ->with(['user', 'category'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('content', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('category', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.posts-index', [
            'posts' => $posts
        ]);
    }
} 