<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class TrainersList extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public int $page = 1;

    protected string $paginationTheme = 'tailwind';

    /**
     * Go to trainer profile using SPA navigation
     * @param int $trainerId - ID of the trainer
     */
    public function viewTrainer($trainerId)
    {
        // Navigate to trainer details page
        return $this->redirect(route('trainer.show', ['trainerId' => $trainerId]), navigate: true);
    }

    public function render()
    {
        $trainers = User::where('role', 'trainer')->paginate(9);
        return view('livewire.trainers-list', compact('trainers'))
            ->layout('layouts.blog');
    }
} 