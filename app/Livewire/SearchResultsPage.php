<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use App\Models\Post;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class SearchResultsPage extends Component
{
    use WithPagination;

    // The ID of the post currently selected for viewing details. Synced with the URL query string.
    #[Url]
    public ?int $selectedPostId = null;
    
    // Stores the current search term entered by the user.
    #[Url(as: 'q')]
    public string $searchQuery = '';

    protected $listeners = [
        'showPostDetails' => 'goToPost', // Listen for event to display a specific post
        'performSearch' => 'updateSearch'  // Listen for event to update search results
    ];

    public function mount()
    {
        // Get query parameter or use the session value
        $queryParam = Request::query('q');
        
        if ($queryParam) {
            // If we have a query parameter, use it and store in session
            $this->searchQuery = $queryParam;
            Session::put('searchQuery', $this->searchQuery);
        } else {
            // Otherwise use session value if available
            $this->searchQuery = Session::get('searchQuery', '');
        }
        
        $this->selectedPostId = Session::get('search_selectedPostId', null);
    }

    /**
     * Sets the selected post ID and persists it to the session.
     */
    public function goToPost($postId)
    {
        $this->selectedPostId = $postId;
        Session::put('search_selectedPostId', $postId);
    }

    /**
     * Clears the selected post ID from the component state and session.
     */
    public function resetSelectedPost()
    {
        $this->selectedPostId = null;
        Session::forget('search_selectedPostId');
    }

    /**
     * Updates the search query and resets the selected post.
     */
    public function updateSearch($query)
    {       
        $this->searchQuery = $query;
        Session::put('searchQuery', $query);
        $this->resetPage();
        $this->resetSelectedPost(); // Clear selection when a new search is performed
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        if ($this->searchQuery && strlen(trim($this->searchQuery)) >= 3) {
            $locale = app()->getLocale();
            
            $posts = Post::query()
                ->where(function($query) {
                    // Wyszukiwanie w oryginalnych polach (zazwyczaj język polski)
                    $query->where('title', 'like', '%'.$this->searchQuery.'%')
                          ->orWhere('content', 'like', '%'.$this->searchQuery.'%');
                })
                ->orWhereHas('translations', function($query) use ($locale) {
                    // Wyszukiwanie w tłumaczeniach (angielski i inne języki)
                    $query->where('locale', $locale)
                          ->where(function($q) {
                              $q->where('title', 'like', '%'.$this->searchQuery.'%')
                                ->orWhere('content', 'like', '%'.$this->searchQuery.'%');
                          });
                })
                ->latest()
                ->paginate(9);
        } else {
            $posts = Post::query()->limit(0)->paginate(9);
        }
        
        return view('livewire.search-results-page', [
            'posts' => $posts
        ]);
    }
}