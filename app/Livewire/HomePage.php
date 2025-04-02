<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;

class HomePage extends Component
{
    #[Url]
    public ?int $selectedPostId = null;

    // Listens for events related to page navigation and post selection
    protected $listeners = [
        'pageChanged'      => 'handlePageChanged',  // Handle page changes
        'showPostDetails'  => 'setPost',             // Set the selected post when viewing details
        'navigateToHome'   => 'resetSelectedPost'    // Reset selected post when navigating to home
    ];

    // Initialize selected post from session, if available
    public function mount()
    {
        $this->selectedPostId = session('home_selectedPostId', null);
    }

    // Handles page changes, resetting selected post if navigating away from home
    public function handlePageChanged($page)
    {
        if ($page !== 'home') {
            $this->resetSelectedPost();
        }
    }

    // Set the selected post and update the URL with post ID
    public function setPost($postId)
    {
        $this->selectedPostId = $postId;
        session(['home_selectedPostId' => $postId]); // Store selected post ID in session
        $this->dispatch('updateUrl', 'home?post=' . $postId); // Update the URL with the post ID
    }

    // Reset the selected post and clear session data
    public function resetSelectedPost()
    {
        $this->selectedPostId = null;
        session()->forget('home_selectedPostId'); // Clear session data
        $this->dispatch('updateUrl', 'home'); // Reset the URL to home without post ID
    }

    // Handle navigating directly to a post
    public function goToPost($postId)
    {
        $this->setPost($postId);
    }

    // Render the view for the home page
    public function render()
    {
        return view('livewire.home-page')
            ->layout('layouts.blog'); // Use the 'blog' layout for the home page
    }
}
