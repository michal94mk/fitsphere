<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class TermsPage extends Component
{
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function handlePageChanged($page)
    {
        if ($page !== 'terms') {
            session()->forget('terms_viewed');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.terms-page');
    }
}
