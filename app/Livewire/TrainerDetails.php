<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trainer;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Layout;
use App\Services\LogService;

class TrainerDetails extends Component
{
    public $trainerId;
    public $trainer;
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }

    public function mount($trainerId)
    {
        try {
            $this->trainerId = $trainerId;
            
            // Load trainer with appropriate translation for current locale
            $locale = App::getLocale();
            $this->trainer = Trainer::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])->findOrFail($trainerId);
        } catch (\Exception $e) {
            // Log error with LogService
            $this->logService->error('Error loading trainer details', [
                'trainer_id' => $trainerId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Nie znaleziono trenera o podanym ID.');
            $this->redirect(route('trainers.list'), navigate: true);
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