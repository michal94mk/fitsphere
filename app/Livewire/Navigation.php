<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class Navigation extends Component
{
    public string $currentPage = 'home';
    public ?string $successMessage = null;
    public string $searchQuery = '';
    public $toastMessage = '';


    protected $listeners = ['pageChanged' => 'updateCurrentPage'];

    public function mount()
    {
        $path = Request::path() ?: 'home';
        $allowedPages = [
            'home', 'posts', 'about', 'contact', 'terms',
            'admin', 'login', 'register', 'forgot-password', 'profile',
            'search'
        ];

        // Jeśli URL jest 'search', ustawiamy currentPage na 'search'
        if ($path === 'search') {
            $this->currentPage = 'search';
        } else {
            $this->currentPage = in_array($path, $allowedPages) ? $path : 'home';
        }
        
        Session::put('currentPage', $this->currentPage);
    }

    public function goToPage($page)
    {
        $this->successMessage = null;

        if ($page === 'home') {
            Session::forget('home_selectedPostId');
            $this->dispatch('navigateToHome');
        } elseif ($page === 'posts') {
            Session::forget('posts_selectedPostId');
            $this->dispatch('navigateToPosts');
        } elseif ($page === 'search') {
            Session::forget('search_selectedPostId');
            $this->goToSearch();
            return;
        }

        $this->currentPage = $page;
        Session::put('currentPage', $page);
        $this->dispatch('navigateToPage', $page);
    }


    public function resetToast()
    {
        $this->toastMessage = '';
    }
    
    public function goToSearch()
    {
        if (empty(trim($this->searchQuery)) || strlen(trim($this->searchQuery)) < 3) {
            $this->toastMessage = 'Wprowadź minimum 3 znaki';
            return;
        }
    
        Session::put('searchQuery', $this->searchQuery);
        
        $this->currentPage = 'search';
        Session::put('currentPage', 'search');
    
        $this->dispatch('navigateToPage', 'search');
        
        $this->dispatch('performSearch', $this->searchQuery);
    }

    public function updateCurrentPage($page)
    {
        $this->currentPage = $page;
        Session::put('currentPage', $page);
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}