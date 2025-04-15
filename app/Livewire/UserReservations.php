<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class UserReservations extends Component
{
    use WithPagination;
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }
    
    public function cancelReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', 'Nie znaleziono rezerwacji.');
            return;
        }
        
        if ($reservation->status === 'completed') {
            session()->flash('error', 'Nie można anulować zakończonej rezerwacji.');
            return;
        }
        
        $reservation->update(['status' => 'cancelled']);
        session()->flash('success', 'Rezerwacja została anulowana.');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.user-reservations', [
            'reservations' => Reservation::where('user_id', Auth::id())
                ->with('trainer')
                ->latest()
                ->paginate(10)
        ]);
    }
}
