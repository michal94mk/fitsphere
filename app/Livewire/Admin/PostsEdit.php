<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

class PostsEdit extends Component
{
    use WithFileUploads, HasFlashMessages;
    
    public $postId;
    public $title = '';
    public $slug;
    public $excerpt;
    public $content = '';
    public $status = 'draft';
    public $category_id = null;
    public $currentImage;
    public $image;
    public $dataLoaded = false;
    
    protected function rules()
    {
        return [
            'title' => 'required|min:3|max:200',
            'slug' => 'required|unique:posts,slug,' . $this->postId,
            'excerpt' => 'nullable|max:500',
            'content' => 'required|min:10|max:15000',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:1024'
        ];
    }
    
    public function updatedTitle($value)
    {
        $this->slug = \Illuminate\Support\Str::slug($value);
    }
    
    public function update()
    {
        $this->validate();

        try {
            $post = Post::findOrFail($this->postId);

            if ($this->image) {
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                }
                $imagePath = $this->image->store('images/posts', 'public');
                $post->image = $imagePath;
            }

            $post->title = $this->title;
            $post->slug = $this->slug;
            $post->excerpt = $this->excerpt;
            $post->content = $this->content;
            $post->status = $this->status;
            $post->category_id = $this->category_id;
            
            $post->save();

            $this->setSuccessMessage(__('admin.post_updated'));
            return redirect()->route('admin.posts.index');
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.post_update_error', ['error' => $e->getMessage()]));
        }
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:1024',
        ]);
    }

    public function removeImage()
    {
        if ($this->currentImage) {
            try {
                if (Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                
                $post = Post::findOrFail($this->postId);
                $post->image = null;
                $post->save();
                
                $this->currentImage = null;
                $this->image = null;
                
                $this->setSuccessMessage(__('admin.image_removed'));
            } catch (\Exception $e) {
                $this->setErrorMessage(__('admin.image_remove_error', ['error' => $e->getMessage()]));
            }
        }
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
    
    #[Layout('layouts.admin', ['header' => 'admin.edit_post'])]
    public function render()
    {
        return view('livewire.admin.posts-edit', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }
} 