<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Trainer;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateReservation extends Component
{
    public $trainerId;
    public $trainer;
    public $date;
    public $startTime;
    public $endTime;
    public $notes;
    
    public function mount($trainerId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->trainerId = $trainerId;
        $this->trainer = Trainer::findOrFail($trainerId);
        $this->date = Carbon::today()->format('Y-m-d');
    }
    
    public function createReservation()
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Sprawdź, czy trener ma już rezerwację w tym czasie
        $existingReservation = Reservation::where('trainer_id', $this->trainerId)
            ->where('date', $this->date)
            ->where(function($query) {
                $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                    ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                    ->orWhere(function($q) {
                        $q->where('start_time', '<=', $this->startTime)
                          ->where('end_time', '>=', $this->endTime);
                    });
            })
            ->exists();
            
        if ($existingReservation) {
            session()->flash('error', 'Ten termin jest już zajęty. Wybierz inny czas.');
            return;
        }
        
        // Utwórz rezerwację
        Reservation::create([
            'user_id' => Auth::id(),
            'trainer_id' => $this->trainerId,
            'date' => $this->date,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);
        
        session()->flash('success', 'Rezerwacja została utworzona i oczekuje na potwierdzenie przez trenera.');
        
        return redirect()->route('user.reservations');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.create-reservation');
    }
}
