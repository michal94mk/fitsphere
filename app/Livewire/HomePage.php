<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\App;

class HomePage extends Component
{
    public $latestPosts;
    public $categories;
    public $popularPosts;
    public $posts;

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
    }
    
    /**
     * Handles the language change event.
     * 
     * Reacts to asynchronous language change in the application.
     * Reloads posts with appropriate translations
     * for the newly selected language.
     * 
     * @param string $locale The selected language code
     * @return void
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