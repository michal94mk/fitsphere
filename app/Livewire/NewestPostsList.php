<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class NewestPostsList extends Component
{
    public function goToPost($postId)
    {
        $this->dispatch('showPostDetails', $postId)->to('home-page');
    }

    public function render()
    {
        $posts = Post::withCount('comments')->latest()->take(5)->get();

        return view('livewire.newest-posts-list', compact('posts'));
    }
}
