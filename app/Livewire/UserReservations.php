<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserReservations extends Component
{
    use WithPagination;
    
    public $showCancelModal = false;
    public $reservationToCancel = null;
    
    public $statusFilter = '';
    public $dateFilter = '';
    public $search = '';
    
    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
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
    
    public function openCancelModal($id)
    {
        $this->reservationToCancel = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$this->reservationToCancel) {
            session()->flash('error', __('trainers.reservation_not_found'));
            return;
        }
        
        $this->showCancelModal = true;
    }
    
    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->reservationToCancel = null;
    }
    
    public function cancelReservation()
    {
        if (!$this->reservationToCancel) {
            session()->flash('error', __('trainers.reservation_not_found'));
            $this->closeCancelModal();
            return;
        }
        
        if ($this->reservationToCancel->status === 'completed') {
            session()->flash('error', __('trainers.cannot_cancel_completed'));
            $this->closeCancelModal();
            return;
        }
        
        $this->reservationToCancel->update(['status' => 'cancelled']);
        session()->flash('success', __('trainers.reservation_cancelled'));
        $this->closeCancelModal();
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        $query = Reservation::where('user_id', Auth::id())
            ->with('trainer');
            
        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        } else {
            // By default, only show non-cancelled reservations
            $query->where('status', '!=', 'cancelled');
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
            $query->whereHas('trainer', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('specialization', 'like', '%' . $this->search . '%');
            });
        }
        
        return view('livewire.user-reservations', [
            'reservations' => $query->latest()->paginate(10)
        ]);
    }
}
