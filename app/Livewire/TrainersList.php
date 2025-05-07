<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Trainer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\App;
use App\Services\LogService;

// Component for displaying and filtering trainers list
class TrainersList extends Component
{
    use WithPagination;

    // URL query parameters
    #[Url]
    public int $page = 1;
    
    #[Url]
    public string $search = '';
    
    #[Url]
    public string $specialization = '';

    protected string $paginationTheme = 'tailwind';
    
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }

    // Redirect to trainer details page
    public function viewTrainer($trainerId)
    {
        return $this->redirect(route('trainer.show', ['trainerId' => $trainerId]), navigate: true);
    }

    // Load trainers with filters and localized content
    protected function loadTrainers()
    {
        try {
            $locale = App::getLocale();
            
            $query = Trainer::with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }]);
            
            // Apply search filter
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
            
            // Apply specialization filter
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
        } catch (\Exception $e) {
            $this->logService->error('Error loading trainers list', [
                'search' => $this->search,
                'specialization' => $this->specialization,
                'error' => $e->getMessage()
            ]);
            
            return collect([])->paginate(12);
        }
    }

    // Render component with trainers data
    #[Layout('layouts.blog')]
    public function render()
    {
        try {
            $trainers = $this->loadTrainers();
            return view('livewire.trainers-list', compact('trainers'));
        } catch (\Exception $e) {
            $this->logService->error('Error rendering trainers list', [
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.trainers_list_error'));
            return view('livewire.trainers-list', ['trainers' => collect([])->paginate(9)]);
        }
    }
} 