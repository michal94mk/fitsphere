<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trainer;
use Livewire\Attributes\Layout;

class TrainerDetails extends Component
{
    public $trainerId;
    public $trainer;

    public function mount($trainerId)
    {
        $this->trainerId = $trainerId;
        $this->loadTrainer();
    }

    public function loadTrainer()
    {
        $this->trainer = Trainer::where('id', $this->trainerId)->first();
        
        if (!$this->trainer) {
            session()->flash('error', 'Nie znaleziono trenera o podanym ID.');
            return $this->redirect(route('trainers.list'), navigate: true);
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        if (!$this->trainer) {
            return $this->redirect(route('trainers.list'), navigate: true);
        }
        
        return view('livewire.trainer-details');
    }
} 