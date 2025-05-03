<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'locale',
        'specialization',
        'description',
        'bio',
        'specialties'
    ];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}