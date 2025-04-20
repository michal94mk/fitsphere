<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class PostsPage extends Component
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

    
    public function goToPost($postId)
    {
        return $this->redirect(route('post.show', ['postId' => $postId]), navigate: true);
    }
    
    /**
     * Reset pagination when filters change
     */
    public function updatedSearchQuery()
    {
        $this->resetPage();
    }
    
    public function updatedCategory()
    {
        $this->resetPage();
    }
    
    public function updatedSortBy()
    {
        $this->resetPage();
    }
    
    /**
     * Handles the language change event.
     * 
     * Reacts to asynchronous language change in the application.
     * Resets pagination to ensure proper display of
     * posts list with translations in the newly selected language.
     * 
     * @param string $locale The selected language code
     * @return void
     */
    #[On('switch-locale')]
    public function handleLanguageChange($locale)
    {
        $this->resetPage();
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        $query = Post::with(['user', 'category']);
        
        // Load post translations for the current locale
        $locale = App::getLocale();
        $query->with(['translations' => function($query) use ($locale) {
            $query->where('locale', $locale);
        }]);
        
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
                $query->orderBy('view_count', 'desc');
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
        ]);
    }
}