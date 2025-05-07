<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class PostsEdit extends Component
{
    use WithFileUploads;
    
    public $postId;
    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $status;
    public $category_id;
    public $currentImage;
    public $image;
    public $dataLoaded = false;
    
    protected function rules()
    {
        return [
            'title' => 'required|min:3',
            'slug' => 'required|unique:posts,slug,' . $this->postId,
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:1024'
        ];
    }
    
    public function updatedTitle($value)
    {
        $this->slug = \Illuminate\Support\Str::slug($value);
    }

    public function mount($id)
    {
        $this->postId = $id;
        $post = Post::findOrFail($id);
        
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->category_id = $post->category_id;
        $this->currentImage = $post->image;
        $this->dataLoaded = true;
    }
    
    public function update()
    {
        $this->validate();
        
        try {
            $post = Post::findOrFail($this->postId);
            $post->title = $this->title;
            $post->slug = $this->slug;
            $post->excerpt = $this->excerpt;
            $post->content = $this->content;
            $post->status = $this->status;
            $post->category_id = $this->category_id;
            
            if ($this->image) {
                // Delete old image if it exists
                if ($post->image && file_exists(storage_path('app/public/' . $post->image))) {
                    unlink(storage_path('app/public/' . $post->image));
                }
                
                $imagePath = $this->image->store('posts', 'public');
                $post->image = $imagePath;
            }
            
            $post->save();
            
            session()->flash('success', 'Post has been successfully updated.');
            return redirect()->route('admin.posts.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the post: ' . $e->getMessage());
        }
    }
    
    #[Layout('layouts.admin', ['header' => 'Edit Post'])]
    public function render()
    {
        return view('livewire.admin.posts-edit', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }
} 