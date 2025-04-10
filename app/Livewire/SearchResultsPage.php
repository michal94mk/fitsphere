<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Post;
use Illuminate\Support\Facades\Session;

class SearchResultsPage extends Component
{
    // The ID of the post currently selected for viewing details. Synced with the URL query string.
    #[Url]
    public ?int $selectedPostId = null;
    
    // Stores the current search term entered by the user.
    public string $searchQuery = '';

    protected $listeners = [
        'showPostDetails' => 'goToPost', // Listen for event to display a specific post
        'performSearch' => 'updateSearch'  // Listen for event to update search results
    ];

    public function mount()
    {
        // Initialize component state from session data on load.
        $this->searchQuery = Session::get('searchQuery', '');
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
        $this->resetSelectedPost(); // Clear selection when a new search is performed
    }

    public function render()
    {
        return view('livewire.search-results-page')
            ->layout('layouts.blog');
    }
}