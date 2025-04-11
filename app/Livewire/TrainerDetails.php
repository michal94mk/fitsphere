<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On; 

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
        $this->trainer = User::where('id', $this->trainerId)
                              ->where('role', 'trainer')
                              ->first();
        
        if (!$this->trainer) {
            session()->flash('error', 'Nie znaleziono trenera o podanym ID.');
            return $this->redirect(route('trainers.list'), navigate: true);
        }
    }

    public function render()
    {
        if (!$this->trainer) {
            return $this->redirect(route('trainers.list'), navigate: true);
        }
        
        return view('livewire.trainer-details')
            ->layout('layouts.blog');
    }
} 