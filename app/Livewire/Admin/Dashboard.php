<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Trainer;
use App\Models\Comment;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $stats = [];
    public $recentUsers = [];
    public $trainerUsers = [];
    public $pendingTrainers = [];
    public $popularPosts = [];
    
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
        ];
        
        // Recent users
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
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
    }
    
    public function approveTrainer($trainerId)
    {
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
        
        session()->flash('success', "Trener {$trainer->name} został zatwierdzony.");
    }
    
    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['header' => 'Dashboard']);
    }
} 