<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Livewire\Component;

class TrainersShow extends Component
{
    public $trainer;
    
    public function mount($id)
    {
        $this->trainer = Trainer::findOrFail($id);
    }
    
    public function render()
    {
        return view('livewire.admin.trainers-show', [
            'trainer' => $this->trainer
        ])->layout('layouts.admin', [
            'header' => 'Szczegóły trenera: ' . $this->trainer->name
        ]);
    }
} 