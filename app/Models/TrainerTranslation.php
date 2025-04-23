<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trainer_id',
        'locale',
        'specialization',
        'description',
        'bio',
        'specialties'
    ];

    /**
     * Get the trainer that this translation belongs to
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
} 