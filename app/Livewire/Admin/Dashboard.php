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
    
    public function approveTrainer($id)
    {
        try {
            $trainer = User::where('role', 'trainer')->findOrFail($id);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Clear cache so other components refresh
            $this->clearCache();
            
            // Refresh pending trainers list and stats
            $this->pendingTrainers = User::where('role', 'trainer')
                ->where('is_approved', false)
                ->latest()
                ->take(5)
                ->get();
            $this->stats['pendingTrainers'] = User::where('role', 'trainer')->where('is_approved', false)->count();
            
            // Send notification email
            try {
                $emailService = app(EmailService::class);
                $emailService->sendTrainerApprovedEmail($trainer);
                session()->flash('success', __('admin.trainer_approved_with_email', ['name' => $trainer->name]));
            } catch (\Exception $e) {
                session()->flash('success', __('admin.trainer_approved_no_email', ['name' => $trainer->name, 'error' => $e->getMessage()]));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('admin.trainer_approve_error', ['error' => $e->getMessage()]));
        }
    }

    protected function clearCache()
    {
        // Clear dashboard cache
        $cacheKey = 'admin.dashboard.' . app()->getLocale();
        cache()->forget($cacheKey);
        
        // Clear trainer list caches with common search/filter combinations
        $searches = ['', ' '];
        $statuses = ['', 'approved', 'pending'];
        $sortFields = ['created_at', 'name', 'email', 'specialization'];
        $sortDirections = ['asc', 'desc'];
        $pages = [1, 2, 3, 4, 5]; // Clear first few pages
        
        foreach ($searches as $search) {
            foreach ($statuses as $status) {
                foreach ($sortFields as $sortField) {
                    foreach ($sortDirections as $sortDirection) {
                        foreach ($pages as $page) {
                            $trainerCacheKey = 'admin.trainers.' . $search . '.' . $status . '.' . $sortField . '.' . $sortDirection . '.' . $page;
                            cache()->forget($trainerCacheKey);
                        }
                    }
                }
            }
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