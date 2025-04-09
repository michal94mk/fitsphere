<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Session;

class SearchResults extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    protected $listeners = [
        'performSearch' => 'updateSearch'
    ];

    public function mount($searchQuery = '')
    {
        $this->searchQuery = $searchQuery ?: Session::get('searchQuery', '');
    }

    public function updateSearch($query)
    {
        $this->resetPage();
        $this->searchQuery = $query;
    }

    public function goToPost($postId)
    {
        Session::put('search_selectedPostId', $postId);
        
        $this->dispatch('showPostDetails', $postId);
    }

    public function render()
    {
        $posts = Post::query()
            ->where(function($query) {
                $query->where('title', 'like', '%'.$this->searchQuery.'%')
                      ->orWhere('content', 'like', '%'.$this->searchQuery.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.search-results', compact('posts'));
    }
}