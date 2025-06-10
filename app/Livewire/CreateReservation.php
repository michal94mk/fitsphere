<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Trainer;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\LogService;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateReservation extends Component
{
    public $trainerId;
    public $trainer;
    public $date;
    public $startTime;
    public $endTime;
    public $notes;
    public $availableTimeSlots = [];
    public $timeSlotGrid = [];
    
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }
    
    public function mount($trainerId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        try {
            $this->trainerId = $trainerId;
            $this->trainer = Trainer::findOrFail($trainerId);
            
            // Always set today's date by default
            $this->date = Carbon::today()->format('Y-m-d');
            
            // Initialize time slots immediately
            $this->updateAvailableTimeSlots();
            
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Trainer not found', [
                'trainer_id' => $trainerId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.trainer_not_found'));
            return redirect()->route('trainers.list');
        } catch (Throwable $e) {
            $this->logService->exception($e, 'Error initializing reservation form', [
                'trainer_id' => $trainerId,
                'step' => 'initialization'
            ]);
            
            session()->flash('error', __('trainers.initialization_error'));
            return redirect()->route('trainers.list');
        }
    }

    public function updatedDate()
    {
        $this->updateAvailableTimeSlots();
        $this->startTime = null;
        $this->endTime = null;
    }
    
    public function updatedStartTime()
    {
        $this->updateAvailableTimeSlots();
    }
    
    public function updatedEndTime()
    {
        $this->updateAvailableTimeSlots();
    }
    
    public function selectTimeSlot($time)
    {
        if (!$this->startTime) {
            // If no start time is selected, set it as start time
            $this->startTime = $time;
        } else if (!$this->endTime && $time > $this->startTime) {
            // If start time is selected and new time is after it, set it as end time
            $this->endTime = $time;
        } else {
            // Otherwise reset and set new start time
            $this->startTime = $time;
            $this->endTime = null;
        }
    }
    
    public function resetTimeSelection()
    {
        $this->startTime = null;
        $this->endTime = null;
    }
    
    public function updateAvailableTimeSlots()
    {
        try {
            // Get all existing reservations for the selected date and trainer
            $existingReservations = Reservation::where('trainer_id', $this->trainerId)
                ->where('date', $this->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('start_time')
                ->get(['start_time', 'end_time']);
            
        // Define training hours
        $startHour = 8;
        $endHour = 20;
        
        $selectedDate = Carbon::parse($this->date);
        $isToday = $selectedDate->isToday();

        // Prepare the time slot grid
        $this->timeSlotGrid = [];
        
        // Generate time slots in 30-minute increments
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $hourSlots = [];
            
            for ($minute = 0; $minute < 60; $minute += 30) {
                $slotTime = Carbon::parse($this->date)->setHour($hour)->setMinute($minute);
                $timeValue = $slotTime->format('H:i');
                
                // Skip past times if today
                if ($isToday && $slotTime->isPast()) {
                    $hourSlots[] = [
                        'time' => $timeValue,
                        'available' => false,
                        'reason' => 'past'
                    ];
                    continue;
                }
                
                $isAvailable = true;
                $reason = '';
                
                // Check for conflicts with existing reservations - uses the same logic as in createReservation
                foreach ($existingReservations as $reservation) {
                    // Create Carbon instances properly using the reservation time
                    $resStart = Carbon::parse($selectedDate->format('Y-m-d'))->setTimeFromTimeString($reservation->start_time);
                    $resEnd = Carbon::parse($selectedDate->format('Y-m-d'))->setTimeFromTimeString($reservation->end_time);
                    
                    // Check if current slot or slot+30 minutes conflicts with reservation
                    $slotTimeEnd = $slotTime->copy()->addMinutes(30);
                    
                    // Case 1: Slot starts during an existing reservation
                    $case1 = $slotTime >= $resStart && $slotTime < $resEnd;
                    // Case 2: Slot end falls into an existing reservation
                    $case2 = $slotTimeEnd > $resStart && $slotTimeEnd <= $resEnd;
                    // Case 3: Slot encompasses an entire existing reservation
                    $case3 = $slotTime <= $resStart && $slotTimeEnd >= $resEnd;
                    
                    if ($case1 || $case2 || $case3) {
                        $isAvailable = false;
                        $reason = 'booked';
                        break;
                    }
                }
                
                $isSelected = false;
                if ($this->startTime && $this->endTime) {
                    $isSelected = $timeValue === $this->startTime || 
                                  $timeValue === $this->endTime || 
                                  ($timeValue > $this->startTime && $timeValue < $this->endTime);
                } else if ($this->startTime) {
                    $isSelected = $timeValue === $this->startTime;
                }
                
                $hourSlots[] = [
                    'time' => $timeValue,
                    'available' => $isAvailable,
                    'reason' => $reason,
                    'selected' => $isSelected
                ];
            }
            
            $this->timeSlotGrid[] = [
                'hour' => sprintf('%02d:00', $hour),
                'slots' => $hourSlots
            ];
        }
        
        } catch (Throwable $e) {
            $this->logService->exception($e, 'Error updating available time slots', [
                'trainer_id' => $this->trainerId,
                'date' => $this->date
            ]);
            
            // Initialize empty time slot grid on error
            $this->timeSlotGrid = [];
            throw $e;
        }
    }
    
    public function createReservation()
    {
        try {
            // Ensure time slots are properly initialized before validation
            $this->updateAvailableTimeSlots();
            
            $this->validate([
                'date' => 'required|date|after_or_equal:today',
                'startTime' => 'required',
                'endTime' => 'required|after:startTime',
                'notes' => 'nullable|string|max:500',
            ], [
                'startTime.required' => __('trainers.select_start_time'),
                'endTime.required' => __('trainers.select_end_time'),
                'endTime.after' => __('trainers.end_time_after_start'),
            ]);
            
            // Improved logic for checking existing reservations
            $existingReservation = Reservation::where('trainer_id', $this->trainerId)
                ->where('date', $this->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function($query) {
                    // Case 1: Reservation start conflicts with selected time range
                    $query->where(function($q) {
                        $q->where('start_time', '>=', $this->startTime)
                          ->where('start_time', '<', $this->endTime);
                    })
                    // Case 2: Reservation end conflicts with selected time range
                    ->orWhere(function($q) {
                        $q->where('end_time', '>', $this->startTime)
                          ->where('end_time', '<=', $this->endTime);
                    })
                    // Case 3: Reservation encompasses the entire selected time range
                    ->orWhere(function($q) {
                        $q->where('start_time', '<=', $this->startTime)
                          ->where('end_time', '>=', $this->endTime);
                    });
                })
                ->exists();
                
            if ($existingReservation) {
                session()->flash('error', __('trainers.time_slot_already_booked'));
                return;
            }
            
            // Create reservation
            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'trainer_id' => $this->trainerId,
                'date' => $this->date,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);
            
            $this->logService->info('New reservation created', [
                'reservation_id' => $reservation->id,
                'trainer_id' => $this->trainerId,
                'user_id' => Auth::id(),
                'date' => $this->date,
                'time_slot' => $this->startTime . ' - ' . $this->endTime,
            ]);
            
            session()->flash('success', __('trainers.reservation_created_pending'));
            
            return redirect()->route('user.reservations');
        } catch (Throwable $e) {
            $this->logService->exception($e, 'Error creating reservation', [
                'trainer_id' => $this->trainerId,
                'date' => $this->date,
                'time_slot' => $this->startTime . ' - ' . $this->endTime,
            ]);
            
            session()->flash('error', __('trainers.reservation_creation_error'));
            return;
        }
    }

    // Add a method to check if selected date is in the past
    public function getIsPastDateProperty()
    {
        if (!$this->date) {
            return false;
        }
        
        $selectedDate = Carbon::parse($this->date);
        return $selectedDate->lt(Carbon::today());
    }

    // Add functionality for changing months and updating dates
    public function previousMonth()
    {
        $this->date = Carbon::parse($this->date)->subMonth()->format('Y-m-d');
        $this->updateAvailableTimeSlots();
    }

    public function nextMonth()
    {
        $this->date = Carbon::parse($this->date)->addMonth()->format('Y-m-d');
        $this->updateAvailableTimeSlots();
    }

    public function updateDate($newDate)
    {
        $this->date = $newDate;
        $this->updateAvailableTimeSlots();
        $this->startTime = null;
        $this->endTime = null;
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.create-reservation');
    }
}
