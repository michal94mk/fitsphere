<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trainer;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Layout;

class TrainerDetails extends Component
{
    public $trainerId;
    public $trainer;

    public function mount($trainerId)
    {
        $this->trainerId = $trainerId;
        
        // Load trainer with appropriate translation for current locale
        $locale = App::getLocale();
        $this->trainer = Trainer::with(['translations' => function($query) use ($locale) {
            $query->where('locale', $locale);
        }])->findOrFail($trainerId);
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