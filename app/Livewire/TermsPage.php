<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;

class TermsPage extends Component
{
    protected $listeners = ['pageChanged' => 'handlePageChanged', 'switch-locale' => 'handleLanguageChange'];

    public function handlePageChanged($page)
    {
        if ($page !== 'terms') {
            session()->forget('terms_viewed');
        }
    }
    
    public function handleLanguageChange($locale)
    {
        // Force re-render when language changes
        $this->dispatch('$refresh');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.terms-page');
    }
}
