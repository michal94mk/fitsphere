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
     * Listen for language change events and update post listings
     * 
     * Updates the displayed posts when the application language changes.
     * Stores the locale in session for persistence between requests
     * and triggers a component refresh.
     *
     * @param string $locale The new language code (en/pl)
     */
    #[On('language-changed')]
    public function handleLanguageChange($locale)
    {
        // Store the locale in Livewire session for persistence
        session()->put('livewire_locale', $locale);
        
        // Reset pagination and refresh the post listing with new translations
        $this->resetPage();
        $this->dispatch('$refresh');
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        $query = Post::with(['user', 'category']);
        
        // Load post translations for the current locale
        // Używaj wartości z sesji Livewire, jeśli istnieje
        $locale = session()->get('livewire_locale', App::getLocale());
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