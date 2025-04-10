<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class PageContent extends Component
{
    // Determines which main content view to display (e.g., 'home', 'login').
    public string $currentPage = 'home';
    // Prevents handling multiple navigation requests simultaneously.
    public bool $isLoading = false;

    protected $listeners = ['navigateToPage' => 'updateContent'];

    public function mount()
    {
        // Initialize with the last viewed page or default to 'home'.
        $this->currentPage = session('currentPage', 'home');
    }

    /**
     * Updates the main content view when the 'navigateToPage' event is received.
     */
    public function updateContent($page)
    {
        // Ignore requests if a page transition is already in progress.
        if ($this->isLoading) {
            return;
        }
        
        $this->isLoading = true;
        
        $this->currentPage = $page;
        session(['currentPage' => $page]); // Persist the current page in the session

        // Allow subsequent updates.
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.page-content');
    }
}