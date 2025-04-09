<?php

namespace App\Livewire;

use Livewire\Component;

class PostsPage extends Component
{
    public ?int $selectedPostId = null;

    protected $listeners = [
        'showPostDetails' => 'goToPost',
        'navigateToPosts' => 'resetSelectedPost'
    ];

    public function mount()
    {
        $this->selectedPostId = session('posts_selectedPostId', null);
    }

    public function goToPost($postId)
    {
        $this->selectedPostId = $postId;
        session(['posts_selectedPostId' => $postId]);
    }

    public function resetSelectedPost()
    {
        $this->reset('selectedPostId');
        session()->forget('posts_selectedPostId');
    }

    public function render()
    {
        return view('livewire.posts-page', [
            'posts' => \App\Models\Post::latest()->get()
        ]);
    }
}
