<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PostsShow extends Component
{
    public $postId;
    public $post;

    public function mount($id)
    {
        $this->postId = $id;
        $this->post = Post::with(['user', 'category', 'comments', 'translations'])->findOrFail($id);
    }

    #[Layout('layouts.admin', ['header' => 'Post Details'])]
    public function render()
    {
        return view('livewire.admin.posts-show');
    }
}
