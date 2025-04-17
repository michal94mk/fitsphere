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
    public $availableTimeSlots = [];
    public $timeSlotGrid = [];
    
    public function mount($trainerId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->trainerId = $trainerId;
        $this->trainer = Trainer::findOrFail($trainerId);
        
        // Always set today's date by default
        $this->date = Carbon::today()->format('Y-m-d');
        
        // Initialize time slots immediately
        $this->updateAvailableTimeSlots();
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
        // Get all existing reservations for the selected date and trainer
        $existingReservations = Reservation::where('trainer_id', $this->trainerId)
            ->where('date', $this->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);
            
        // Define training hours (e.g., 8:00 AM to 8:00 PM)
        $startHour = 8; // 8:00 AM
        $endHour = 20;  // 8:00 PM
        
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
                
                // Sprawdzanie kolizji z wcześniejszymi rezerwacjami - używa tej samej logiki co w createReservation
                foreach ($existingReservations as $reservation) {
                    // Create Carbon instances properly using the reservation time
                    $resStart = Carbon::parse($selectedDate->format('Y-m-d'))->setTimeFromTimeString($reservation->start_time);
                    $resEnd = Carbon::parse($selectedDate->format('Y-m-d'))->setTimeFromTimeString($reservation->end_time);
                    
                    // Sprawdź, czy obecny slot lub slot+30 minut koliduje z rezerwacją
                    $slotTimeEnd = $slotTime->copy()->addMinutes(30);
                    
                    // Przypadek 1: Slot zaczyna się w trakcie istniejącej rezerwacji
                    $case1 = $slotTime >= $resStart && $slotTime < $resEnd;
                    // Przypadek 2: Koniec slotu wpada w istniejącą rezerwację
                    $case2 = $slotTimeEnd > $resStart && $slotTimeEnd <= $resEnd;
                    // Przypadek 3: Slot obejmuje całą istniejącą rezerwację
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
    }
    
    public function createReservation()
    {
        // Ensure time slots are properly initialized before validation
        $this->updateAvailableTimeSlots();
        
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
            'notes' => 'nullable|string|max:500',
        ], [
            'startTime.required' => 'Wybierz godzinę rozpoczęcia.',
            'endTime.required' => 'Wybierz godzinę zakończenia.',
            'endTime.after' => 'Godzina zakończenia musi być późniejsza niż godzina rozpoczęcia.',
        ]);
        
        // Poprawiona logika sprawdzania istniejących rezerwacji
        $existingReservation = Reservation::where('trainer_id', $this->trainerId)
            ->where('date', $this->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) {
                // Przypadek 1: początek rezerwacji koliduje z wybranym przedziałem
                $query->where(function($q) {
                    $q->where('start_time', '>=', $this->startTime)
                      ->where('start_time', '<', $this->endTime);
                })
                // Przypadek 2: koniec rezerwacji koliduje z wybranym przedziałem
                ->orWhere(function($q) {
                    $q->where('end_time', '>', $this->startTime)
                      ->where('end_time', '<=', $this->endTime);
                })
                // Przypadek 3: rezerwacja obejmuje cały wybrany przedział
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
