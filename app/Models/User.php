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
        'password',
        'role',
        'image',
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
        ];
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
    
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
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
    
    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }
}
