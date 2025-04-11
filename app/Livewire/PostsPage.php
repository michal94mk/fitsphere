<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

class PostsPage extends Component
{
    
    public $searchQuery = '';
    public $category = '';
    public $sortBy = 'newest';
    
    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    
    public function goToPost($postId)
    {
        return $this->redirect(route('post.show', ['postId' => $postId]), navigate: true);
    }
    
    public function render()
    {
        $query = Post::query();
        
        if (!empty($this->searchQuery)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('content', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        if (!empty($this->category)) {
            $query->where('category_id', $this->category);
        }
        
        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $posts = $query->paginate(9);
        $categories = Category::all();
        
        return view('livewire.posts-page', [
            'posts' => $posts,
            'categories' => $categories,
        ])->layout('layouts.blog');
    }
}