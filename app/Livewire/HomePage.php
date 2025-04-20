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
     * Load posts with translations for current locale
     * 
     * Fetches latest and most popular posts with their translations
     * for the specified language. Uses locale from session if available
     * or falls back to application default.
     *
     * @param string|null $locale Optional language code to load specific translations
     */
    protected function loadPosts($locale = null)
    {
        $locale = $locale ?? session()->get('livewire_locale', App::getLocale());
        
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
     * Listen for language change events and update homepage content
     * 
     * Updates displayed posts when application language changes.
     * Stores the selected locale in session for persistence and
     * refreshes the component with translated content.
     *
     * @param string $locale The new language code (en/pl)
     */
    #[On('language-changed')]
    public function handleLanguageChange($locale)
    {
        // Store locale in session for persistence
        session()->put('livewire_locale', $locale);
        
        // Reload posts with the new locale and refresh the view
        $this->loadPosts($locale);
        $this->dispatch('$refresh');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.home-page');
    }
}