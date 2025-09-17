<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostsCreate extends Component
{
    use WithFileUploads, HasFlashMessages;
    
    public $title = '';
    public $slug;
    public $excerpt;
    public $content = '';
    public $status = 'draft';
    public $category_id = null;
    public $image;
    
    protected $rules = [
        'title' => 'required|min:3|max:200',
        'slug' => 'required|unique:posts',
        'excerpt' => 'nullable|max:500',
        'content' => 'required|min:10|max:15000',
        'status' => 'required|in:published,draft',
        'category_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|max:1024'
    ];
    
    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }
    
    public function removeImage()
    {
        $this->image = null;
    }
    
    public function store()
    {
        $this->validate();
        
        $post = new Post();
        $post->title = $this->title;
        $post->slug = $this->slug;
        $post->excerpt = $this->excerpt;
        $post->content = $this->content;
        $post->status = $this->status;
        $post->category_id = $this->category_id;
        $post->user_id = Auth::id();
        
        if ($this->image) {
            $imagePath = $this->image->store('images/posts', 'public');
            $post->image = $imagePath;
        }
        
        $post->save();
        
        $this->setSuccessMessage(__('admin.post_created_success'));
        return redirect()->route('admin.posts.index');
    }
    
    #[Layout('layouts.admin', ['header' => 'admin.add_new_post'])]
    public function render()
    {
        return view('livewire.admin.posts-create', [
            'categories' => Category::orderBy('name')->get()
        ]);
    }
} 