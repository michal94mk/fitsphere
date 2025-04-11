<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostsIndex extends Component
{
    use WithPagination;
    
    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        // Usuń plik obrazka jeśli istnieje
        if ($post->image && file_exists(storage_path('app/public/' . $post->image))) {
            unlink(storage_path('app/public/' . $post->image));
        }
        $post->delete();
        session()->flash('success', 'Post został pomyślnie usunięty.');
    }
    
    public function render()
    {
        return view('livewire.admin.posts-index', [
            'posts' => Post::latest()->paginate(10)
        ])->layout('layouts.admin', ['header' => 'Lista postów']);
    }
} 