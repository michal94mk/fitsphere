<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AboutPage extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public int $page = 1;

    protected string $paginationTheme = 'tailwind';

    public function render()
    {
        $trainers = User::where('role', 'trainer')->paginate(9);
        return view('livewire.about-page', compact('trainers'))
            ->layout('layouts.blog');
    }
}
