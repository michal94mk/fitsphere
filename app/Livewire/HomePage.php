<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\App;
use App\Services\LogService;

class HomePage extends Component
{
    public $latestPosts;
    public $categories;
    public $popularPosts;
    public $posts;
    
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }

    public function mount()
    {
        $this->loadPosts();
    }
    
    /**
     * Loads posts with translations for current locale
     * 
     * Fetches latest and most popular posts with their translations
     * for the current application locale.
     */
    protected function loadPosts()
    {
        try {
            $locale = App::getLocale();
            
            // Fetch latest posts with translations, user data, and comment counts
            $this->latestPosts = Post::with(['user', 'category'])
                ->with(['translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }])
                ->withCount('comments')
                ->latest()
                ->take(3)
                ->get();
                
            $this->categories = Category::withCount('posts')->get();
            
            // Fetch most popular posts with translations (by view count)
            $this->popularPosts = Post::with(['user', 'category'])
                ->with(['translations' => function($query) use ($locale) {
                    $query->where('locale', $locale);
                }])
                ->withCount('comments')
                ->orderBy('view_count', 'desc')
                ->take(3)
                ->get();
                
            // Retain compatibility with existing view structure
            $this->posts = $this->latestPosts;
        } catch (\Exception $e) {
            // Log error with LogService
            $this->logService->error('Error loading homepage posts', [
                'error' => $e->getMessage(),
                'locale' => App::getLocale()
            ]);
            
            // Initialize with empty collections to prevent errors in the view
            $this->latestPosts = collect([]);
            $this->categories = collect([]);
            $this->popularPosts = collect([]);
            $this->posts = collect([]);
            
            session()->flash('error', __('common.data_loading_error'));
        }
    }
    
    /**
     * Handles the language change event.
     * 
     * Reacts to asynchronous language change in the application.
     * Reloads posts with appropriate translations
     * for the newly selected language.
     */
    #[On('switch-locale')]
    public function handleLanguageChange($locale)
    {
        $this->loadPosts();
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.home-page');
    }
}