<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostsEdit extends Component
{
    use WithFileUploads;
    
    public $post;
    public $image;
    
    protected function rules()
    {
        return [
            'post.title' => 'required|min:3',
            'post.slug' => 'required|unique:posts,slug,' . $this->post->id,
            'post.content' => 'required',
            'post.status' => 'required|in:published,draft',
            'post.category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:1024'
        ];
    }
    
    public function mount($id)
    {
        $this->post = Post::findOrFail($id);
    }
    
    public function update()
    {
        $this->validate();
        
        if ($this->image) {
            // Usuń stare zdjęcie jeśli istnieje
            if ($this->post->image && file_exists(storage_path('app/public/' . $this->post->image))) {
                unlink(storage_path('app/public/' . $this->post->image));
            }
            
            $imagePath = $this->image->store('posts', 'public');
            $this->post->image = $imagePath;
        }
        
        $this->post->save();
        
        session()->flash('success', 'Post został pomyślnie zaktualizowany.');
        return redirect()->route('admin.posts.index');
    }
    
    public function render()
    {
        return view('livewire.admin.posts-edit', [
            'categories' => Category::all()
        ])->layout('layouts.admin', ['header' => 'Edytuj post']);
    }
} 