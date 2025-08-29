<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\User;
use App\Livewire\Admin\Traits\HasFlashMessages;
use App\Services\EmailService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use HasFlashMessages;
    public function approveTrainer($trainerId)
    {
        // Check if user is admin
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Access denied');
        }
        
        try {
            $trainer = User::findOrFail($trainerId);
            $trainer->is_approved = true;
            $trainer->save();
            
            // Sending an email notification
            try {
                $emailService = new EmailService();
                $emailService->sendTrainerApprovedEmail($trainer);
                $this->setSuccessMessage(__('admin.trainer_approved_with_email', ['name' => $trainer->name]));
            } catch (\Exception $e) {
                $this->setSuccessMessage(__('admin.trainer_approved_no_email', ['name' => $trainer->name, 'error' => $e->getMessage()]));
            }
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.trainer_approve_error', ['error' => $e->getMessage()]));
        }
    }

    #[Layout('layouts.admin', ['header' => 'Dashboard'])]
    public function render()
    {
        // Basic stats - these are small queries, no need to cache for real-time accuracy
        $totalUsers = User::count();
        $totalTrainers = User::where('role', 'like', '%trainer%')->count();
        $totalPosts = Post::count();
        $totalReservations = Reservation::count();
        $totalCategories = Category::count();
        $totalComments = Comment::count();
        
        // Stats array for the view
        $stats = [
            'users' => User::where('role', 'not like', '%trainer%')->count(),
            'trainers' => $totalTrainers,
            'posts' => $totalPosts,
            'comments' => $totalComments,
            'pendingTrainers' => User::where('role', 'like', '%trainer%')->where('is_approved', false)->count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'draftPosts' => Post::where('status', 'draft')->count(),
        ];
        
        // Recent activity with eager loading to prevent N+1 queries
        $recentUsers = User::with('translations')
            ->latest()
            ->take(5)
            ->get();
            
        $popularPosts = Post::with(['user', 'translations'])
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();
            
        $draftPosts = Post::with(['user', 'translations'])
            ->where('status', 'draft')
            ->latest()
            ->take(5)
            ->get();
            
        $pendingTrainers = User::where('role', 'like', '%trainer%')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();
            
        $recentComments = Comment::with(['user', 'post'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('livewire.admin.dashboard', compact(
            'stats',
            'totalUsers',
            'totalTrainers', 
            'totalPosts',
            'totalReservations',
            'totalCategories',
            'totalComments',
            'recentUsers',
            'popularPosts',
            'draftPosts',
            'pendingTrainers',
            'recentComments'
        ));
    }
} 