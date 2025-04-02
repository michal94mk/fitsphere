<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostsList extends Component
{
    public function render()
    {
        $posts = Post::all();
        return view('livewire.posts-list', compact('posts'))
            ->layout('layouts.blog');
    }

    public function goToPost($postId)
    {
        $this->dispatch('showPostDetails', $postId);
    }
}
