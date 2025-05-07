<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\LogService;

class TrainerReservations extends Component
{
    use WithPagination;
    
    // Filters and search properties
    public string $statusFilter = '';
    public string $dateFilter = '';
    public string $search = '';
    
    // Counters for different reservation statuses
    public int $pendingCount = 0;
    public int $confirmedCount = 0;
    public int $completedCount = 0;
    public int $cancelledCount = 0;
    
    // Query string parameters
    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }
    
    // Initialize component and check authentication
    public function mount()
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.login');
        }
        
        $this->loadReservationCounts();
    }
    
    // Load counts for reservation statuses
    public function loadReservationCounts()
    {
        try {
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
        } catch (\Throwable $e) {
            $this->handleError($e, 'Error loading reservation counts');
        }
    }
    
    // Reset pagination when updating filters
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
    
    // Change reservation status to confirmed
    public function confirmReservation($id)
    {
        try {
            $reservation = $this->getReservationForTrainer($id);
            
            if (!$reservation) {
                session()->flash('error', __('trainers.reservation_not_found'));
                return;
            }
            
            $reservation->update(['status' => 'confirmed']);
            $this->loadReservationCounts();
            session()->flash('success', __('trainers.reservation_confirmed'));
        } catch (\Throwable $e) {
            $this->handleError($e, 'Error confirming reservation');
        }
    }
    
    // Change reservation status to cancelled
    public function cancelReservation($id)
    {
        try {
            $reservation = $this->getReservationForTrainer($id);
            
            if (!$reservation) {
                session()->flash('error', __('trainers.reservation_not_found'));
                return;
            }
            
            $reservation->update(['status' => 'cancelled']);
            $this->loadReservationCounts();
            session()->flash('success', __('trainers.reservation_cancelled'));
        } catch (\Throwable $e) {
            $this->handleError($e, 'Error cancelling reservation');
        }
    }
    
    // Change reservation status to completed
    public function completeReservation($id)
    {
        try {
            $reservation = $this->getReservationForTrainer($id);
            
            if (!$reservation) {
                session()->flash('error', __('trainers.reservation_not_found'));
                return;
            }
            
            $reservation->update(['status' => 'completed']);
            $this->loadReservationCounts();
            session()->flash('success', __('trainers.reservation_completed'));
        } catch (\Throwable $e) {
            $this->handleError($e, 'Error completing reservation');
        }
    }
    
    // Get reservation that belongs to current trainer
    protected function getReservationForTrainer($id)
    {
        return Reservation::where('id', $id)
            ->where('trainer_id', Auth::guard('trainer')->id())
            ->first();
    }
    
    // Handle errors with logging
    protected function handleError(\Throwable $e, string $message)
    {
        $this->logService->error($message, [
            'trainer_id' => Auth::guard('trainer')->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('error', __('common.unexpected_error'));
    }

    // Render the component with filtered reservations
    #[Layout('layouts.trainer')]
    public function render()
    {
        try {
            $trainerId = Auth::guard('trainer')->id();
            $query = Reservation::where('trainer_id', $trainerId)
                ->with('user');
                
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }
            
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
            
            if ($this->search) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }
            
            return view('livewire.trainer-reservations', [
                'reservations' => $query->latest()->paginate(10)
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e, 'Error loading reservations');
            return view('livewire.trainer-reservations', [
                'reservations' => collect([])
            ]);
        }
    }
}
