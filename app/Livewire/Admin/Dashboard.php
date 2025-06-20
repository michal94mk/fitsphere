<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Post;
// use App\Models\Trainer; // Removed - trainers are now Users with role='trainer'
use App\Models\Comment;
use App\Models\Category;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;

class Dashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $trainerUsers = [];
    public $pendingTrainers = [];
    public $popularPosts = [];
    public $draftPosts = [];
    
    public function mount()
    {
        // Basic statistics
        $this->stats = [
            'users' => User::where('role', 'user')->count(),
            'trainers' => User::where('role', 'trainer')->count(),
            'posts' => Post::count(),
            'comments' => Comment::count() ?? 0,
            'bookings' => 0, // Placeholder for bookings stats
            'pendingTrainers' => User::where('role', 'trainer')->where('is_approved', false)->count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'draftPosts' => Post::where('status', 'draft')->count(),
        ];
        
        // Recent users - include additional role information
        $this->recentUsers = User::latest()->take(5)->get();
            
        // All trainers
        $this->trainerUsers = User::where('role', 'trainer')->latest()->take(5)->get();
        
        // Pending trainers
        $this->pendingTrainers = User::where('role', 'trainer')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();
            
        // Popular posts
        $this->popularPosts = Post::withCount('views')
            ->withCount('comments')
            ->with(['user', 'translations'])
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();
            
        // Draft posts
        $this->draftPosts = Post::where('status', 'draft')
            ->with(['user', 'translations'])
            ->latest()
            ->take(5)
            ->get();
    }
    
    public function approveTrainer($trainerId)
    {
        try {
            // Find and update the trainer (now a User with role='trainer')
            $trainer = User::where('role', 'trainer')->findOrFail($trainerId);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Clear dashboard cache
            $cacheKey = 'admin.dashboard.' . App::getLocale();
            cache()->forget($cacheKey);
            
            // Update the pending trainers list
            $this->pendingTrainers = User::where('role', 'trainer')
                ->where('is_approved', false)
                ->latest()
                ->take(5)
                ->get();
                
            // Update the stats
            $this->stats['pendingTrainers'] = User::where('role', 'trainer')->where('is_approved', false)->count();
            
            // Send notification email using the email service
            $emailService = app(EmailService::class);
            $result = $emailService->sendTrainerApprovedEmail($trainer);
            
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
        $cacheKey = 'admin.dashboard.' . App::getLocale();
        
        $data = cache()->remember($cacheKey, now()->addMinutes(15), function () {
            return [
                'totalUsers' => User::count(),
                'totalPosts' => Post::count(),
                'totalCategories' => Category::count(),
                'pendingTrainers' => User::where('role', 'trainer')
                    ->where('is_approved', false)
                    ->count(),
                'popularPosts' => Post::with(['user', 'translations'])
                    ->orderBy('view_count', 'desc')
                    ->take(5)
                    ->get(),
                'draftPosts' => Post::with(['user', 'translations'])
                    ->where('status', 'draft')
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentUsers' => User::latest()
                    ->take(5)
                    ->get(),
            ];
        });

        return view('livewire.admin.dashboard', $data);
    }
} 