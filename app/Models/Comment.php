<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the author of the comment
     */
    public function author()
    {
        return $this->user;
    }

    /**
     * Check if the comment belongs to the authenticated user
     */
    public function belongsToAuthUser(): bool
    {
        if (Auth::check()) {
            return $this->user_id === Auth::id();
        }
        
        return false;
    }
}
