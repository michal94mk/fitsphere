<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrainerApproved;
use Livewire\Attributes\Layout;

class TrainersShow extends Component
{
    public $trainer;
    
    public function mount($id)
    {
        $this->trainer = Trainer::findOrFail($id);
    }
    
    public function disapproveTrainer()
    {
        try {
            $this->trainer->is_approved = false;
            $this->trainer->save();
            
            session()->flash('success', "Status trenera {$this->trainer->name} został zmieniony na oczekujący.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zmiany statusu trenera: {$e->getMessage()}");
        }
    }

    #[Layout('layouts.admin', ['header' => 'Szczegóły trenera'])]
    public function render()
    {
        return view('livewire.admin.trainers-show', [
            'trainer' => $this->trainer
        ]);
    }
    
    public function approveTrainer()
    {
        try {
            $this->trainer->is_approved = true;
            $this->trainer->save();
            
            // Wysyłka emaila z powiadomieniem
            try {
                Mail::to($this->trainer->email)->send(new TrainerApproved($this->trainer));
                session()->flash('success', "Trener {$this->trainer->name} został zatwierdzony, a powiadomienie email zostało wysłane.");
            } catch (\Exception $e) {
                session()->flash('success', "Trener {$this->trainer->name} został zatwierdzony, ale wystąpił błąd podczas wysyłania powiadomienia email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zatwierdzania trenera: {$e->getMessage()}");
        }
    }
} 