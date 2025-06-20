<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\EmailService;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TrainersShow extends Component
{
    use HasFlashMessages;
    
    public $trainer;
    
    public function mount($id)
    {
        $this->trainer = User::where('role', 'trainer')->findOrFail($id);
    }
    
    public function disapproveTrainer()
    {
        $this->clearMessages();
        
        try {
            $this->trainer->is_approved = false;
            $this->trainer->save();
            
            $this->setSuccessMessage("Status trenera {$this->trainer->name} został zmieniony na oczekujący.");
        } catch (\Exception $e) {
            $this->setErrorMessage("Wystąpił błąd podczas zmiany statusu trenera: {$e->getMessage()}");
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
        $this->clearMessages();
        
        try {
            $this->trainer->is_approved = true;
            $this->trainer->save();
            
            // Wysyłka emaila z powiadomieniem
            try {
                $emailService = new EmailService();
                $emailService->sendTrainerApprovedEmail($this->trainer);
                $this->setSuccessMessage("Trener {$this->trainer->name} został zatwierdzony, a powiadomienie email zostało wysłane.");
            } catch (\Exception $e) {
                $this->setSuccessMessage("Trener {$this->trainer->name} został zatwierdzony, ale wystąpił błąd podczas wysyłania powiadomienia email: {$e->getMessage()}");
            }
        } catch (\Exception $e) {
            $this->setErrorMessage("Wystąpił błąd podczas zatwierdzania trenera: {$e->getMessage()}");
        }
    }
} 