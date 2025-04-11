<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesShow extends Component
{
    use WithPagination;
    
    public $category;
    
    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
    }
    
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
        return view('livewire.admin.categories-show', [
            'posts' => Post::where('category_id', $this->category->id)->latest()->paginate(10)
        ])->layout('layouts.admin', ['header' => 'Posty w kategorii: ' . $this->category->name]);
    }
} 