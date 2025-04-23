<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Trainer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\App;

class TrainersList extends Component
{
    use WithPagination;

    #[Url]
    public int $page = 1;
    
    #[Url]
    public string $search = '';
    
    #[Url]
    public string $specialization = '';

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

    protected function loadTrainers()
    {
        $locale = App::getLocale();
        
        $query = Trainer::with(['translations' => function($query) use ($locale) {
            $query->where('locale', $locale);
        }]);
        
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('specialization', 'like', $search)
                  ->orWhereHas('translations', function($q) use ($search) {
                      $q->where('specialization', 'like', $search);
                  });
            });
        }
        
        if ($this->specialization && $this->specialization !== 'all') {
            $specialization = '%' . $this->specialization . '%';
            $query->where(function($q) use ($specialization) {
                $q->where('specialties', 'like', $specialization)
                  ->orWhereHas('translations', function($q) use ($specialization) {
                      $q->where('specialties', 'like', $specialization);
                  });
            });
        }
        
        return $query->orderBy('name')->paginate(12);
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        $trainers = $this->loadTrainers();
        return view('livewire.trainers-list', compact('trainers'));
    }
} 