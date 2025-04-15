<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class TrainerReservations extends Component
{
    use WithPagination;
    
    public function mount()
    {
        // Sprawdź, czy zalogowany jest trener
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.login');
        }
    }
    
    public function confirmReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', 'Nie znaleziono rezerwacji.');
            return;
        }
        
        $reservation->update(['status' => 'confirmed']);
        session()->flash('success', 'Rezerwacja została potwierdzona.');
    }
    
    public function cancelReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', 'Nie znaleziono rezerwacji.');
            return;
        }
        
        $reservation->update(['status' => 'cancelled']);
        session()->flash('success', 'Rezerwacja została anulowana.');
    }
    
    public function completeReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', 'Nie znaleziono rezerwacji.');
            return;
        }
        
        $reservation->update(['status' => 'completed']);
        session()->flash('success', 'Rezerwacja została oznaczona jako zakończona.');
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer-reservations', [
            'reservations' => Reservation::where('trainer_id', Auth::guard('trainer')->id())
                ->with('user')
                ->latest()
                ->paginate(10)
        ]);
    }
}
