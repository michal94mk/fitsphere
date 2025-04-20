<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Navigation extends Component
{
    public string $currentPage = 'home';
    public string $searchQuery = '';
    public string $toastMessage = '';

    public function mount()
    {
        $this->updateCurrentPage();
    }
    
    protected function updateCurrentPage()
    {
        // Set the current page based on the URL path
        $path = Request::path();
        
        // Extract base path for pages with parameters
        if (str_contains($path, '/')) {
            $path = explode('/', $path)[0];
        }
        
        // Map path to page names
        if ($path === '' || $path === '/') {
            $this->currentPage = 'home';
        } elseif ($path === 'post') {
            $this->currentPage = 'posts';
        } elseif ($path === 'trainer' || $path === 'trainers') {
            $this->currentPage = 'trainers';
        } elseif ($path === 'nutrition-calculator') {
            $this->currentPage = 'nutrition-calculator';
        } elseif ($path === 'meal-planner') {
            $this->currentPage = 'meal-planner';
        } elseif (in_array($path, ['home', 'posts', 'contact', 'terms', 'search', 'login', 'register', 'forgot-password', 'profile'])) {
            $this->currentPage = $path;
        } else {
            $this->currentPage = 'home';
        }
    }
    
    /**
     * Handles the language change event.
     * 
     * Reacts to asynchronous language change in the application.
     * Resets search state and messages in navigation
     * to maintain consistency after language change.
     * 
     * @param string $locale The selected language code
     * @return void
     */
    #[On('switch-locale')]
    public function handleLanguageChange($locale)
    {
        $this->searchQuery = '';
        $this->toastMessage = '';
        $this->currentPage = '';
    }

    public function resetToast()
    {
        $this->toastMessage = '';
    }
    
    public function goToSearch()
    {
        if (empty(trim($this->searchQuery)) || strlen(trim($this->searchQuery)) < 3) {
            $this->toastMessage = __('common.search_min_chars');
            return;
        }
    
        // Set the current page to search
        $this->currentPage = 'search';
        
        // Redirect to search results page with the query
        return $this->redirect(route('search', ['q' => $this->searchQuery]), navigate: true);
    }

    public function isAdmin()
    {
        // Only users with 'admin' role have access to the admin panel
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }
        
        return false;
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}