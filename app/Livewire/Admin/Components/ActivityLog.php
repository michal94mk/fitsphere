<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Component
{
    public $activities = [];
    public $loading = true;
    
    /**
     * Mount the component and load recent activities
     */
    public function mount()
    {
        $this->loadActivities();
    }
    
    /**
     * Load recent activities from various tables
     */
    private function loadActivities()
    {
        // Get recent post activities
        $postActivities = DB::table('posts')
            ->select(
                'id', 
                DB::raw("'post' as type"), 
                DB::raw("CASE WHEN created_at = updated_at THEN 'created' ELSE 'updated' END as action"), 
                'title as name', 
                'created_at',
                'updated_at',
                'user_id'
            )
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();
            
        // Get recent comment activities
        $commentActivities = DB::table('comments')
            ->select(
                'id',
                DB::raw("'comment' as type"),
                DB::raw("'created' as action"),
                DB::raw("CONCAT(SUBSTRING(content, 1, 30), '...') as name"),
                'created_at',
                'created_at as updated_at',
                'user_id'
            )
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
            
        // Get recent user registrations
        $userActivities = DB::table('users')
            ->select(
                'id',
                DB::raw("'user' as type"),
                DB::raw("'registered' as action"),
                'name',
                'created_at',
                'created_at as updated_at',
                'id as user_id'
            )
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
            
        // Merge all activities, sort by date and limit to 15
        $allActivities = $postActivities->merge($commentActivities)
            ->merge($userActivities)
            ->sortByDesc('updated_at')
            ->take(10);
            
        // Load user data for each activity
        $activityData = [];
        foreach ($allActivities as $activity) {
            $user = DB::table('users')
                ->select('name')
                ->where('id', $activity->user_id)
                ->first();
                
            $activityData[] = [
                'id' => $activity->id,
                'type' => $activity->type,
                'action' => $activity->action,
                'name' => $activity->name,
                'time' => $activity->updated_at,
                'user' => $user ? $user->name : 'Unknown',
                'user_avatar' => null
            ];
        }
        
        $this->activities = $activityData;
        $this->loading = false;
    }
    
    /**
     * Render the activity log component
     */
    public function render()
    {
        return view('livewire.admin.components.activity-log');
    }
} 