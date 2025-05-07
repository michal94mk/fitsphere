<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainerReservations extends Component
{
    use WithPagination;
    
    public $statusFilter = '';
    public $dateFilter = '';
    public $search = '';
    
    public $pendingCount = 0;
    public $confirmedCount = 0;
    public $completedCount = 0;
    public $cancelledCount = 0;
    
    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    public function mount()
    {
        // Sprawdź, czy zalogowany jest trener
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.login');
        }
        
        $this->loadReservationCounts();
    }
    
    public function loadReservationCounts()
    {
        $trainerId = Auth::guard('trainer')->id();
        
        $this->pendingCount = Reservation::where('trainer_id', $trainerId)
            ->where('status', 'pending')
            ->count();
            
        $this->confirmedCount = Reservation::where('trainer_id', $trainerId)
            ->where('status', 'confirmed')
            ->count();
            
        $this->completedCount = Reservation::where('trainer_id', $trainerId)
            ->where('status', 'completed')
            ->count();
            
        $this->cancelledCount = Reservation::where('trainer_id', $trainerId)
            ->where('status', 'cancelled')
            ->count();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatingDateFilter()
    {
        $this->resetPage();
    }
    
    public function confirmReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', __('trainers.reservation_not_found'));
            return;
        }
        
        $reservation->update(['status' => 'confirmed']);
        $this->loadReservationCounts();
        session()->flash('success', __('trainers.reservation_confirmed'));
    }
    
    public function cancelReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', __('trainers.reservation_not_found'));
            return;
        }
        
        $reservation->update(['status' => 'cancelled']);
        $this->loadReservationCounts();
        session()->flash('success', __('trainers.reservation_cancelled'));
    }
    
    public function completeReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
            
        if (!$reservation) {
            session()->flash('error', __('trainers.reservation_not_found'));
            return;
        }
        
        $reservation->update(['status' => 'completed']);
        $this->loadReservationCounts();
        session()->flash('success', __('trainers.reservation_completed'));
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        $trainerId = Auth::guard('trainer')->id();
        $query = Reservation::where('trainer_id', $trainerId)
            ->with('user');
            
        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        // Apply date filter
        if ($this->dateFilter) {
            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();
            
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('date', $today);
                    break;
                case 'tomorrow':
                    $query->whereDate('date', $tomorrow);
                    break;
                case 'this_week':
                    $query->whereBetween('date', [$today, $today->copy()->endOfWeek()]);
                    break;
                case 'next_week':
                    $nextWeekStart = $today->copy()->addWeek()->startOfWeek();
                    $nextWeekEnd = $nextWeekStart->copy()->endOfWeek();
                    $query->whereBetween('date', [$nextWeekStart, $nextWeekEnd]);
                    break;
            }
        }
        
        // Apply search
        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        return view('livewire.trainer-reservations', [
            'reservations' => $query->latest()->paginate(10)
        ]);
    }
}
