<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\LogService;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        if (!$user || !in_array('trainer', explode(',', $user->role))) {
            return redirect()->route('login');
        }
        
        $this->loadReservationCounts();
    }
    
    // Load counts for reservation statuses
    public function loadReservationCounts()
    {
        try {
            $user = Auth::user();
            $trainerId = $user->id;
            
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
            $user = Auth::user();
            if (!$user || !in_array('trainer', explode(',', $user->role))) {
                session()->flash('error', 'Unauthorized access.');
                return;
            }
            
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
            $user = Auth::user();
            if (!$user || !in_array('trainer', explode(',', $user->role))) {
                session()->flash('error', 'Unauthorized access.');
                return;
            }
            
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
            $user = Auth::user();
            if (!$user || !in_array('trainer', explode(',', $user->role))) {
                session()->flash('error', 'Unauthorized access.');
                return;
            }
            
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
            ->where('trainer_id', Auth::user()->id)
            ->first();
    }
    
    // Handle errors with logging
    protected function handleError(\Throwable $e, string $message)
    {
        $this->logService->error($message, [
            'trainer_id' => Auth::user()->id,
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
            $user = Auth::user();
            $trainerId = $user->id;
            
            $reservationsQuery = Reservation::with(['user', 'trainer'])
                ->where('trainer_id', $trainerId)
                ->orderBy('date', 'desc');
            
            // Apply filters
            if ($this->statusFilter) {
                $reservationsQuery->where('status', $this->statusFilter);
            }
            
            if ($this->dateFilter) {
                $today = Carbon::today();
                switch ($this->dateFilter) {
                    case 'today':
                        $reservationsQuery->whereDate('date', $today);
                        break;
                    case 'this_week':
                        $reservationsQuery->whereBetween('date', [$today->startOfWeek(), $today->endOfWeek()]);
                        break;
                    case 'this_month':
                        $reservationsQuery->whereMonth('date', $today->month);
                        break;
                }
            }
            
            if ($this->search) {
                $reservationsQuery->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }
            
            $reservations = $reservationsQuery->paginate(10);
            
            return view('livewire.trainer-reservations', compact('reservations'));
        } catch (\Exception $e) {
            Log::error('Error rendering trainer reservations: ' . $e->getMessage());
            return view('livewire.trainer-reservations', ['reservations' => collect()]);
        }
    }

    public function updateStatus($reservationId, $status)
    {
        try {
            $user = Auth::user();
            if (!$user || !in_array('trainer', explode(',', $user->role))) {
                session()->flash('error', 'Unauthorized access.');
                return;
            }
            
            $reservation = Reservation::where('id', $reservationId)
                ->where('trainer_id', $user->id)
                ->first();
                
            if (!$reservation) {
                session()->flash('error', 'Reservation not found.');
                return;
            }
            
            $reservation->update([
                'status' => $status,
                'trainer_id' => $user->id,
            ]);
            
            session()->flash('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating reservation status: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the status.');
        }
    }
}
