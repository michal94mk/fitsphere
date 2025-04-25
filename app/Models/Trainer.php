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

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trainers';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the posts for the trainer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    
    /**
     * Get the reservations for the trainer.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
    
    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
    
    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Use Gravatar as fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=160";
    }

    /**
     * Get all translations for this trainer
     */
    public function translations()
    {
        return $this->hasMany(TrainerTranslation::class);
    }

    /**
     * Get translation for the specified locale
     * 
     * @param string|null $locale
     * @return \App\Models\TrainerTranslation|null
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Check if the trainer has a translation for the specified locale
     * 
     * @param string|null $locale
     * @return bool
     */
    public function hasTranslation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }

    /**
     * Get translated specialization in current locale or fallback to original
     * 
     * @return string|null
     */
    public function getTranslatedSpecialization()
    {
        $translation = $this->translation();
        return $translation && $translation->specialization ? $translation->specialization : $this->specialization;
    }

    /**
     * Get translated description in current locale or fallback to original
     * 
     * @return string|null
     */
    public function getTranslatedDescription()
    {
        $translation = $this->translation();
        return $translation && $translation->description ? $translation->description : $this->description;
    }

    /**
     * Get translated bio in current locale or fallback to original
     * 
     * @return string|null
     */
    public function getTranslatedBio()
    {
        $translation = $this->translation();
        return $translation && $translation->bio ? $translation->bio : $this->bio;
    }

    /**
     * Get translated specialties in current locale or fallback to original
     * 
     * @return string|null
     */
    public function getTranslatedSpecialties()
    {
        $translation = $this->translation();
        return $translation && $translation->specialties ? $translation->specialties : $this->specialties;
    }
} 