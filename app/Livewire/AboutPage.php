<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AboutPage extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    
    #[\Livewire\Attributes\Url]
    public ?int $trainerId = null;
    
    protected $queryString = [
        'trainerId' => ['except' => null, 'as' => 'trainer']
    ];

    protected $listeners = [
        'showTrainerProfile' => 'goToTrainer',
        'navigateToTrainers' => 'resetSelectedTrainer'
    ];

    public function mount($trainerId = null)
    {
        if ($trainerId) {
            $trainer = User::where('id', $trainerId)
                ->where('role', 'trainer')
                ->first();
            
            if (!$trainer) {
                // Generuj odpowiedź 404 dla nieistniejących trenerów
                abort(404, 'Trener nie został znaleziony');
            }
            
            $this->trainerId = (int) $trainerId;
        }
    }

    public function goToTrainer($trainerId)
    {
        // Sprawdzamy, czy trener istnieje
        $trainer = User::where('id', $trainerId)
                    ->where('role', 'trainer')
                    ->first();
        
        if (!$trainer) {
            session()->flash('error', 'Nie znaleziono trenera o podanym ID.');
            return;
        }
        
        // Przekierowanie do profilu trenera z użyciem nawigacji SPA
        return $this->redirect(route('about', ['trainerId' => $trainerId]), navigate: true);
    }

    public function resetSelectedTrainer()
    {
        // Przekierowanie do listy trenerów z użyciem nawigacji SPA
        return $this->redirect(route('about'), navigate: true);
    }

    public function showTrainerProfile($trainerId)
    {
        $trainer = User::where('id', $trainerId)
                      ->where('role', 'trainer')
                      ->first();
                      
        if ($trainer) {
            $this->trainerId = $trainerId;
        } else {
            session()->flash('error', 'Nie znaleziono profilu wybranego trenera.');
            return $this->redirect(route('about'), navigate: true);
        }
    }

    
    public function render()
    {
        $trainers = User::where('role', 'trainer')->paginate(9);
        return view('livewire.about-page', compact('trainers'))
            ->layout('layouts.blog');
    }
}
