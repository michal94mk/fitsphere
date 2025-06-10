<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'trainer_id', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the author of the comment (user or trainer)
     */
    public function author()
    {
        return $this->user_id ? $this->user : $this->trainer;
    }

    /**
     * Check if the comment belongs to a specific user (regular user or trainer)
     */
    public function belongsToAuthUser(): bool
    {
        if (Auth::check()) {
            return $this->user_id === Auth::id();
        }
        
        if (Auth::guard('trainer')->check()) {
            return $this->trainer_id === Auth::guard('trainer')->id();
        }
        
        return false;
    }
}
