<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
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
    public $selectedDate;
    public $startTime;
    public $endTime;
    public $notes;
    public $availableTimeSlots = [];
    public $timeSlots = [];
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
            // Load the trainer by ID
            $this->trainerId = $trainerId;
            $this->trainer = User::whereRaw("FIND_IN_SET('trainer', role)")->findOrFail($trainerId);
            
            $this->selectedDate = now()->format('Y-m-d');
            $this->date = $this->selectedDate;
            $this->timeSlots = $this->getAvailableTimeSlots();
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Trainer not found', [
                'trainer_id' => $trainerId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('trainers.trainer_not_found'));
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
    
    public function getAvailableTimeSlots()
    {
        // Initialize with empty array, will be populated by updateAvailableTimeSlots
        return [];
    }
    
    public function updateAvailableTimeSlots()
    {
        try {
            // Get all existing reservations for the selected date and trainer
            $existingReservations = Reservation::where('trainer_id', $this->trainer->id)
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
                'trainer_id' => $this->trainer->id,
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
            $existingReservation = Reservation::where('trainer_id', $this->trainer->id)
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
            
            // Determine client details based on user role
            $user = Auth::user();
            $clientData = [];
            
            if ($user->role === 'user') {
                $clientData = [
                    'user_id' => $user->id, // For backward compatibility
                    'client_id' => $user->id,
                    'client_type' => User::class,
                ];
            } elseif ($user->role === 'trainer') {
                $clientData = [
                    'client_id' => $user->id,
                    'client_type' => User::class,
                ];
            }
            
            // Create reservation
            $reservation = Reservation::create([
                'trainer_id' => $this->trainer->id,
                'date' => $this->date,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'status' => 'pending',
                'notes' => $this->notes,
            ] + $clientData);
            
            $this->logService->info('New reservation created', [
                'reservation_id' => $reservation->id,
                'trainer_id' => $this->trainer->id,
                'user_id' => Auth::id(),
                'date' => $this->date,
                'time_slot' => $this->startTime . ' - ' . $this->endTime,
            ]);
            
            session()->flash('success', __('trainers.reservation_created_pending'));
            
            return redirect()->route('user.reservations');
        } catch (Throwable $e) {
            $this->logService->exception($e, 'Error creating reservation', [
                'trainer_id' => $this->trainer->id,
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

    public function confirmReservation()
    {
        if (!$this->selectedTime) {
            $this->addError('selectedTime', __('trainers.select_time'));
            return;
        }

        $this->validate([
            'selectedDate' => ['required', 'date', 'after_or_equal:today'],
            'selectedTime' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $dateTime = Carbon::createFromFormat('Y-m-d H:i', $this->selectedDate . ' ' . $this->selectedTime);
            
            if (!$dateTime || $dateTime->isPast()) {
                $this->addError('selectedTime', __('trainers.invalid_date_time'));
                return;
            }

            $conflictingReservation = Reservation::where('trainer_id', $this->trainer->id)
                ->where('date', $dateTime)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($conflictingReservation) {
                $this->addError('selectedTime', __('trainers.time_not_available'));
                return;
            }

            $authenticatedUser = Auth::user();
            
            $reservationData = [
                'trainer_id' => $this->trainer->id,
                'date' => $dateTime,
                'status' => 'pending',
                'notes' => $this->notes,
            ];

            // Set appropriate client relationship
            if ($authenticatedUser->role === 'user') {
                $reservationData['user_id'] = $authenticatedUser->id;
            } elseif ($authenticatedUser->role === 'trainer') {
                $reservationData['client_id'] = $authenticatedUser->id;
                $reservationData['client_type'] = User::class;
            }

            Reservation::create($reservationData);

            // Success message is handled by createReservation method to avoid duplication
            return redirect()->route('user.reservations');
        } catch (Throwable $e) {
            $this->logService->exception($e, 'Error confirming reservation', [
                'trainer_id' => $this->trainer->id,
                'date' => $this->selectedDate,
                'time' => $this->selectedTime,
            ]);
            
            session()->flash('error', __('trainers.reservation_confirmation_error'));
            return;
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.create-reservation');
    }
}
