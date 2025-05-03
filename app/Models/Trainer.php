<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, MustVerifyEmailTrait;

    protected $table = 'trainers';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'specialization',
        'description',
        'image',
        'bio',
        'is_approved',
        'specialties',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
    
    public function getEmailForVerification(): string
    {
        return $this->email;
    }
    
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=160";
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TrainerTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function hasTranslation($locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }

    public function getTranslatedSpecialization(): ?string
    {
        $translation = $this->translation();
        return $translation && $translation->specialization ? $translation->specialization : $this->specialization;
    }

    public function getTranslatedDescription(): ?string
    {
        $translation = $this->translation();
        return $translation && $translation->description ? $translation->description : $this->description;
    }

    public function getTranslatedBio(): ?string
    {
        $translation = $this->translation();
        return $translation && $translation->bio ? $translation->bio : $this->bio;
    }

    public function getTranslatedSpecialties(): ?string
    {
        $translation = $this->translation();
        return $translation && $translation->specialties ? $translation->specialties : $this->specialties;
    }
}