<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserReservations extends Component
{
    use WithPagination;
    
    public bool $showCancelModal = false;
    public ?Reservation $reservationToCancel = null;
    
    public string $statusFilter = '';
    public string $dateFilter = '';
    public string $search = '';
    
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
        try {
            $authenticatedUser = Auth::user();
            
            // Check if this reservation belongs to the authenticated user
            $query = Reservation::where('id', $id);
            
            if (str_contains($authenticatedUser->role, 'user') && !str_contains($authenticatedUser->role, 'admin') && !str_contains($authenticatedUser->role, 'trainer')) {
                // For regular users, check user_id
                $query->where('user_id', $authenticatedUser->id);
            } elseif (str_contains($authenticatedUser->role, 'admin')) {
                // For admins, allow all reservations (no additional filtering)
                // Admin can cancel any reservation
            } elseif (str_contains($authenticatedUser->role, 'trainer')) {
                // For trainers, check client relationship  
                $query->where(function($q) use ($authenticatedUser) {
                    $q->where('user_id', $authenticatedUser->id) // backward compatibility
                      ->orWhere(function($q2) use ($authenticatedUser) {
                          $q2->where('client_id', $authenticatedUser->id)
                             ->where('client_type', get_class($authenticatedUser));
                      });
                });
            }
            
            $this->reservationToCancel = $query->first();
            
            if (!$this->reservationToCancel) {
                $this->addError('cancel', 'Rezerwacja nie została znaleziona lub nie masz uprawnień do jej anulowania.');
                return;
            }
            
            $this->showCancelModal = true;
        } catch (\Exception $e) {
            $this->handleError($e, 'Failed to prepare reservation cancellation');
        }
    }
    
    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->reservationToCancel = null;
    }
    
    public function cancelReservation()
    {
        try {
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
        } catch (\Exception $e) {
            $this->handleError($e, 'Error cancelling reservation');
            $this->closeCancelModal();
        }
    }
    
    protected function handleError(\Exception $e, string $message)
    {
        $authenticatedUser = Auth::user();
        
        Log::error($message, [
            'user_id' => $authenticatedUser ? $authenticatedUser->id : null,
            'user_type' => $authenticatedUser ? get_class($authenticatedUser) : null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        session()->flash('error', __('common.unexpected_error'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        try {
            $authenticatedUser = Auth::user();
            
            // Build query based on authentication type
            if (str_contains($authenticatedUser->role, 'user') && !str_contains($authenticatedUser->role, 'admin') && !str_contains($authenticatedUser->role, 'trainer')) {
                // For regular users, show reservations where they are the client
                $query = Reservation::where('user_id', $authenticatedUser->id)
                    ->with('trainer');
            } elseif (str_contains($authenticatedUser->role, 'admin')) {
                // For admins, show all reservations in the system
                $query = Reservation::with(['trainer', 'user']);
            } elseif (str_contains($authenticatedUser->role, 'trainer')) {
                // For trainers, show reservations where they are the client
                $query = Reservation::where(function($q) use ($authenticatedUser) {
                    $q->where('user_id', $authenticatedUser->id) // backward compatibility
                      ->orWhere(function($q2) use ($authenticatedUser) {
                          $q2->where('client_id', $authenticatedUser->id)
                             ->where('client_type', get_class($authenticatedUser));
                      });
                })->with('trainer');
            } else {
                // Fallback, should not happen due to mount check
                return redirect()->route('login');
            }
                
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
        } catch (\Exception $e) {
            $this->handleError($e, 'Error loading reservations');
            return view('livewire.user-reservations', [
                'reservations' => collect([])
            ]);
        }
    }
}
