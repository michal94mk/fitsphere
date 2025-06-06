<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Trainer;
use App\Models\Comment;
use App\Models\Category;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $trainerUsers = [];
    public $pendingTrainers = [];
    public $popularPosts = [];
    public $draftPosts = [];
    
    protected $emailService;
    
    public function mount(EmailService $emailService)
    {
        $this->emailService = $emailService;
        
        // Basic statistics
        $this->stats = [
            'users' => User::count(),
            'trainers' => Trainer::count(),
            'posts' => Post::count(),
            'comments' => Comment::count() ?? 0,
            'bookings' => 0, // Placeholder for bookings stats
            'pendingTrainers' => Trainer::where('is_approved', false)->count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'draftPosts' => Post::where('status', 'draft')->count(),
        ];
        
        // Recent users - include additional role information
        $this->recentUsers = User::latest()->take(5)->get();
            
        // All trainers
        $this->trainerUsers = Trainer::latest()->take(5)->get();
        
        // Pending trainers
        $this->pendingTrainers = Trainer::where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();
            
        // Popular posts
        $this->popularPosts = Post::withCount('views')
            ->withCount('comments')
            ->with('user')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
            
        // Draft posts
        $this->draftPosts = Post::where('status', 'draft')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
    }
    
    public function approveTrainer($trainerId)
    {
        try {
            // Find and update the trainer
            $trainer = Trainer::findOrFail($trainerId);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Update the pending trainers list
            $this->pendingTrainers = Trainer::where('is_approved', false)
                ->latest()
                ->take(5)
                ->get();
                
            // Update the stats
            $this->stats['pendingTrainers'] = Trainer::where('is_approved', false)->count();
            
            // Send notification email using the email service
            $result = $this->emailService->sendTrainerApprovedEmail($trainer);
            
            // Flash appropriate message based on the result
            if ($result) {
                session()->flash('success', "Trainer {$trainer->name} has been approved and notification email has been sent.");
            } else {
                // Still show success for trainer approval, but note the email issue
                session()->flash('success', "Trainer {$trainer->name} has been approved, but there was an error sending the notification email.");
                // Log additional details
                Log::warning('Failed to send trainer approval email', [
                    'trainer_id' => $trainerId,
                    'trainer_email' => $trainer->email
                ]);
            }
        } catch (\Exception $e) {
            // Log the exception with all details
            Log::error('Error in trainer approval process', [
                'trainer_id' => $trainerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Flash error message
            session()->flash('error', "An error occurred while approving the trainer: {$e->getMessage()}");
        }
    }
    
    #[Layout('layouts.admin', ['header' => 'Dashboard'])]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
} 