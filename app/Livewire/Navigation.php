<?php

namespace App\Livewire;

use Livewire\Component;

class Navigation extends Component
{
    public string $currentPage = 'home';
    public ?string $successMessage = null;

    // Listens for page change event
    protected $listeners = ['pageChanged' => 'goToPage'];

    // Initialize component by setting current page based on the URL
    public function mount()
    {
        // Get current URL path or default to 'home'
        $path = request()->path() ?: 'home';

        // Define allowed pages
        $allowedPages = ['home', 'posts', 'about', 'contact', 'terms', 'admin'];

        // Check if the path is an allowed page, otherwise default to 'home'
        $this->currentPage = in_array($path, $allowedPages) ? $path : 'home';

        // Store current page in session
        session(['currentPage' => $this->currentPage]);
    }

    // Handle page navigation logic
    public function goToPage($page)
    {
        $this->successMessage = null;

        // Clear session data for specific pages
        if ($page === 'home') {
            session()->forget('home_selectedPostId');
            $this->dispatch('navigateToHome');
        } elseif ($page === 'posts') {
            session()->forget('posts_selectedPostId');
            $this->dispatch('navigateToPosts');
        }

        // Update current page and store it in session
        $this->currentPage = $page;
        session(['currentPage' => $page]);
    }

    // Render the component's view
    public function render()
    {
        return view('livewire.navigation');
    }
}
