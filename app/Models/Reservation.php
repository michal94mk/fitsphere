<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trainer_id',
        'client_id',
        'client_type',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function getClientNameAttribute(): string
    {
        if ($this->client) {
            return $this->client->name;
        }
        
        // Fallback to user relationship for backward compatibility
        if ($this->user) {
            return $this->user->name;
        }
        
        return 'Unknown Client';
    }
}
