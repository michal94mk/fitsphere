<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public string $currentPage = 'home';
    public string $searchQuery = '';
    public string $toastMessage = '';

    public function mount()
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
            $this->currentPage = 'trainers'; // Changed from 'about' to 'trainers' for clarity
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
    
        // Set the current page to search
        $this->currentPage = 'search';
        
        // Redirect to search results page with the query
        return $this->redirect(route('search', ['q' => $this->searchQuery]), navigate: true);
    }

    public function isAdmin()
    {
        // Tylko użytkownicy z rolą 'admin' mają dostęp do panelu administratora
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