<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

/**
 * Terms and conditions page component
 */
class TermsPage extends Component
{
    protected $listeners = ['pageChanged' => 'handlePageChanged', 'switch-locale' => 'handleLanguageChange'];

    /**
     * Handle page changes to reset terms viewed status
     */
    public function handlePageChanged($page)
    {
        if ($page !== 'terms') {
            session()->forget('terms_viewed');
        }
    }
    
    /**
     * Handle language changes and refresh the component
     */
    public function handleLanguageChange($locale)
    {
        $this->dispatch('$refresh');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.terms-page');
    }
}
