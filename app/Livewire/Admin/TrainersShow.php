<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\EmailService;
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
    
    public function disapproveTrainer()
    {
        try {
            $this->trainer->is_approved = false;
            $this->trainer->save();
            
            // Reload trainer to get fresh data
            $this->loadTrainer();
            
            session()->flash('success', "Status trenera {$this->trainer->name} został zmieniony na oczekujący.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zmiany statusu trenera: {$e->getMessage()}");
        }
    }

    public function approveTrainer()
    {
        try {
            $this->trainer->is_approved = true;
            $this->trainer->save();
            
            // Reload trainer to get fresh data
            $this->loadTrainer();
            
            // Wysyłka emaila z powiadomieniem
            try {
                $emailService = new EmailService();
                $emailService->sendTrainerApprovedEmail($this->trainer);
                session()->flash('success', "Trener {$this->trainer->name} został zatwierdzony, a powiadomienie email zostało wysłane.");
            } catch (\Exception $e) {
                session()->flash('success', "Trener {$this->trainer->name} został zatwierdzony, ale wystąpił błąd podczas wysyłania powiadomienia email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas zatwierdzania trenera: {$e->getMessage()}");
        }
    }

    #[Layout('layouts.admin', ['header' => 'Szczegóły trenera'])]
    public function render()
    {
        return view('livewire.admin.trainers-show', [
            'trainer' => $this->trainer
        ]);
    }
} 