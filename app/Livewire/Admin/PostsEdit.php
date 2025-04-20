<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

/**
 * Admin Posts Edit Component
 * 
 * This component manages the editing of existing blog posts,
 * including content updates and image uploads.
 */
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
    
    /**
     * Define validation rules for post editing
     * 
     * @return array
     */
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
    
    /**
     * Auto-generate slug from title
     */
    public function updatedTitle($value)
    {
        $this->slug = \Illuminate\Support\Str::slug($value);
    }
    
    /**
     * Initialize the component with the post data
     * 
     * @param int $id The ID of the post to edit
     * @return void
     */
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
    
    /**
     * Update the post with validated data
     * 
     * Handles image uploads and replacement if a new image is provided.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
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
            
            session()->flash('success', 'Post został pomyślnie zaktualizowany.');
            return redirect()->route('admin.posts.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas aktualizacji posta: ' . $e->getMessage());
        }
    }
    
    /**
     * Render the post edit form with categories
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.admin', ['header' => 'Edytuj post'])]
    public function render()
    {
        return view('livewire.admin.posts-edit', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }
} 