<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TrainersShow extends Component
{
    public $trainer;
    public $trainerId;
    
    public function mount($id)
    {
        $this->trainerId = $id;
        $this->loadTrainer();
    }
    
    private function loadTrainer()
    {
        $this->trainer = User::where('role', 'trainer')->findOrFail($this->trainerId);
    }
    


    #[Layout('layouts.admin', ['header' => 'admin.trainer_details'])]
    public function render()
    {
        return view('livewire.admin.trainers-show', [
            'trainer' => $this->trainer
        ]);
    }
} 