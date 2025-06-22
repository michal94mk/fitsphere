<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, MustVerifyEmailTrait;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'image',
        'specialization',
        'experience',
        'description',
        'biography',
        'is_approved',
        'provider',
        'provider_id',
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
    
    // Role management methods
    public function hasRole(string $role): bool
    {
        $roles = explode(',', $this->role);
        return in_array($role, $roles);
    }
    
    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }
    
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    
    public function addRole(string $role): void
    {
        $roles = explode(',', $this->role);
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->role = implode(',', $roles);
            $this->save();
        }
    }
    
    public function removeRole(string $role): void
    {
        $roles = explode(',', $this->role);
        $roles = array_filter($roles, fn($r) => $r !== $role);
        $this->role = implode(',', $roles);
        $this->save();
    }
    
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Use gravatar as fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=160";
    }
    
    // Relationships
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    
    public function trainerReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'trainer_id');
    }
    
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function nutritionalProfile(): HasOne
    {
        return $this->hasOne(NutritionalProfile::class);
    }
    
    public function nutritionalProfiles(): HasMany
    {
        return $this->hasMany(NutritionalProfile::class);
    }
    
    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }
    
    public function translations(): HasMany
    {
        return $this->hasMany(UserTranslation::class);
    }
    
    // Translation methods (for trainers)
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
        if (!$this->isTrainer()) {
            return null;
        }
        
        $translation = $this->translation();
        return $translation && $translation->specialization ? $translation->specialization : $this->specialization;
    }

    public function getTranslatedDescription(): ?string
    {
        if (!$this->isTrainer()) {
            return null;
        }
        
        $translation = $this->translation();
        return $translation && $translation->description ? $translation->description : $this->description;
    }

    public function getTranslatedBio(): ?string
    {
        if (!$this->isTrainer()) {
            return null;
        }
        
        $translation = $this->translation();
        return $translation && $translation->bio ? $translation->bio : $this->bio;
    }

    public function getTranslatedSpecialties(): ?string
    {
        if (!$this->isTrainer()) {
            return null;
        }
        
        $translation = $this->translation();
        return $translation && $translation->specialties ? $translation->specialties : $this->specialties;
    }
    
    // Scopes for trainers
    public function scopeTrainers($query)
    {
        return $query->whereRaw("FIND_IN_SET('trainer', role)");
    }
    
    public function scopeApprovedTrainers($query)
    {
        return $query->trainers()->where('is_approved', true);
    }

    protected static function boot()
    {
        parent::boot();
    }

    public function getProfileDataAttribute()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getStatisticsAttribute()
    {
        return [
            'posts_count' => $this->posts()->count(),
            'comments_count' => $this->comments()->count(),
            'views_count' => $this->posts()->sum('view_count'),
        ];
    }

    public function getFullName(): string
    {
        return $this->name;
    }
}
