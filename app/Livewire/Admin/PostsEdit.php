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
    
    /**
     * Post model instance being edited
     * 
     * @var \App\Models\Post
     */
    public $post;
    
    /**
     * Temporary uploaded image for the post
     * 
     * @var \Livewire\TemporaryUploadedFile|null
     */
    public $image;
    
    /**
     * Define validation rules for post editing
     * 
     * @return array
     */
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
    
    /**
     * Initialize the component with the post data
     * 
     * @param int $id The ID of the post to edit
     * @return void
     */
    public function mount($id)
    {
        $this->post = Post::findOrFail($id);
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
        
        if ($this->image) {
            // Delete old image if it exists
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
    
    /**
     * Render the post edit form with categories
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.admin', ['header' => 'Edytuj post'])]
    public function render()
    {
        return view('livewire.admin.posts-edit', [
            'categories' => Category::all()
        ]);
    }
} 