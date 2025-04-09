<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Post;
use Illuminate\Support\Facades\Session;

class SearchResultsPage extends Component
{
    #[Url]
    public ?int $selectedPostId = null;
    public string $searchQuery = '';

    protected $listeners = [
        'showPostDetails' => 'goToPost',
        'performSearch' => 'updateSearch'
    ];

    public function mount()
    {
        $this->searchQuery = Session::get('searchQuery', '');
        $this->selectedPostId = Session::get('search_selectedPostId', null);
    }

    public function goToPost($postId)
    {
        $this->selectedPostId = $postId;
        Session::put('search_selectedPostId', $postId);
    }

    public function resetSelectedPost()
    {
        $this->selectedPostId = null;
        Session::forget('search_selectedPostId');
    }

    public function updateSearch($query)
    {       
        $this->searchQuery = $query;
        $this->resetSelectedPost();
    }

    public function render()
    {
        return view('livewire.search-results-page')
            ->layout('layouts.blog');
    }
}