<?php

namespace App\Livewire;

use Livewire\Component;

class PageContent extends Component
{
    public string $currentPage = 'home';

    protected $listeners = ['navigateToPage' => 'updateContent'];

    public function mount()
    {
        $this->currentPage = session('currentPage', 'home');
    }

    public function updateContent($page)
    {
        $this->currentPage = $page;
    }

    public function render()
    {
        return view('livewire.page-content');
    }
}
