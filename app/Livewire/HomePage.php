<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Layout;

class HomePage extends Component
{
    public $latestPosts;
    public $categories;
    public $popularPosts;
    public $posts;

    public function mount()
    {
        // Najnowsze posty
        $this->latestPosts = Post::with(['user', 'category'])
            ->withCount('comments')
            ->latest()
            ->take(3)
            ->get();
            
        $this->categories = Category::withCount('posts')->get();
        
        // Popularne posty (według ilości wyświetleń)
        $this->popularPosts = Post::with(['user', 'category'])
            ->withCount('comments')
            ->orderBy('view_count', 'desc')
            ->take(3)
            ->get();
            
        // Ta zmienna też będzie zawierać najnowsze posty - pozostawiam dla kompatybilności z widokiem
        $this->posts = $this->latestPosts;
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.home-page');
    }
}