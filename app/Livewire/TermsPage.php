<?php

namespace App\Livewire;

use Livewire\Component;

class TermsPage extends Component
{
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function handlePageChanged($page)
    {
        if ($page !== 'terms') {
            session()->forget('terms_viewed');
        }
    }

    public function render()
    {
        return view('livewire.terms-page')
            ->layout('layouts.blog');
    }
}
