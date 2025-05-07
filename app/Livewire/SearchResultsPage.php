<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use App\Models\Post;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use App\Services\LogService;
use Throwable;

class SearchResultsPage extends Component
{
    use WithPagination;

    #[Url]
    public ?int $selectedPostId = null;
    
    #[Url(as: 'q')]
    public string $searchQuery = '';

    protected $logService;

    protected $listeners = [
        'showPostDetails' => 'goToPost',
        'performSearch' => 'updateSearch'
    ];

    public function boot()
    {
        $this->logService = app(LogService::class);
    }

    public function mount()
    {
        try {
            $queryParam = Request::query('q');
            
            if ($queryParam) {
                $this->searchQuery = $queryParam;
                Session::put('searchQuery', $this->searchQuery);
            } else {
                $this->searchQuery = Session::get('searchQuery', '');
            }
            
            $this->selectedPostId = Session::get('search_selectedPostId', null);
        } catch (Throwable $e) {
            $this->handleError($e, 'Error initializing search');
        }
    }

    public function goToPost($postId)
    {
        try {
            $this->selectedPostId = $postId;
            Session::put('search_selectedPostId', $postId);
        } catch (Throwable $e) {
            $this->handleError($e, 'Error selecting post');
        }
    }

    public function resetSelectedPost()
    {
        try {
            $this->selectedPostId = null;
            Session::forget('search_selectedPostId');
        } catch (Throwable $e) {
            $this->handleError($e, 'Error resetting post selection');
        }
    }

    public function updateSearch($query)
    {       
        try {
            $this->searchQuery = $query;
            Session::put('searchQuery', $query);
            $this->resetPage();
            $this->resetSelectedPost();
        } catch (Throwable $e) {
            $this->handleError($e, 'Error updating search');
        }
    }
    
    protected function handleError(Throwable $e, string $message)
    {
        $this->logService->exception($e, $message, [
            'search_query' => $this->searchQuery,
        ]);
        
        session()->flash('error', __('common.unexpected_error'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        try {
            if ($this->searchQuery && strlen(trim($this->searchQuery)) >= 3) {
                $locale = app()->getLocale();
                
                $posts = Post::query()
                    ->where(function($query) {
                        $query->where('title', 'like', '%'.$this->searchQuery.'%')
                              ->orWhere('content', 'like', '%'.$this->searchQuery.'%');
                    })
                    ->orWhereHas('translations', function($query) use ($locale) {
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
        } catch (Throwable $e) {
            $this->handleError($e, 'Error performing search');
            return view('livewire.search-results-page', [
                'posts' => collect([])->paginate(9)
            ]);
        }
    }
}