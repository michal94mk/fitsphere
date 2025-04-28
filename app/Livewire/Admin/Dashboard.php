<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Trainer;
use App\Models\Comment;
use App\Models\Category;
use App\Mail\TrainerApproved;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

/**
 * Admin Dashboard Component
 * 
 * This component serves as the main dashboard for administrators,
 * providing system statistics, recent users, pending trainers approval,
 * and other key metrics for monitoring the application.
 */
class Dashboard extends Component
{
    /**
     * General statistics counters for the dashboard
     * 
     * @var array
     */
    public $stats = [];
    
    /**
     * Collection of recently registered users
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $recentUsers = [];
    
    /**
     * Collection of registered trainers
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $trainerUsers = [];
    
    /**
     * Collection of trainers awaiting approval
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $pendingTrainers = [];
    
    /**
     * Collection of most popular posts by view count
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $popularPosts = [];
    
    /**
     * Email service instance
     * 
     * @var EmailService
     */
    protected $emailService;
    
    /**
     * Constructor with dependency injection
     * 
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
        parent::__construct();
    }
    
    /**
     * Initialize the dashboard component and load required data
     * 
     * @return void
     */
    public function mount()
    {
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
    }
    
    /**
     * Approve a trainer account and send notification email
     * 
     * This action changes a trainer's status to approved,
     * refreshes the pending trainers list, updates dashboard stats,
     * and sends an approval notification email to the trainer.
     * 
     * @param int $trainerId The ID of the trainer to approve
     * @return void
     */
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
            $result = $this->emailService->send(
                $trainer->email,
                new TrainerApproved($trainer),
                "Trainer {$trainer->name} has been approved and notification email has been sent."
            );
            
            // Flash appropriate message based on the result
            if ($result['status'] === 'success') {
                session()->flash('success', $result['message']);
            } else {
                // Still show success for trainer approval, but note the email issue
                session()->flash('success', "Trainer {$trainer->name} has been approved, but there was an error sending the notification email: {$result['message']}");
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
    
    /**
     * Render the admin dashboard view with collected data
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.admin', ['header' => 'Dashboard'])]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
} 