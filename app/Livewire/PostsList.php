<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;

class PostsList extends Component
{
    use WithPagination;
    
    public $searchQuery = '';
    public $category = '';
    public $sortBy = 'newest';
    
    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];
    
    public function updatingSearchQuery()
    {
        $this->resetPage();
    }
    
    public function updatingCategory()
    {
        $this->resetPage();
    }
    
    public function updatingSortBy()
    {
        $this->resetPage();
    }
    
    public function search()
    {
        $this->resetPage();
    }
    
    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->resetPage();
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
        
        return view('livewire.posts-list', [
            'posts' => $posts,
            'categories' => $categories,
        ])->layout('layouts.blog');
    }

    public function goToPost($postId)
    {
        $this->dispatch('showPostDetails', $postId);
    }
}