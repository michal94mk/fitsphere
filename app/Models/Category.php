<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }
    
    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        $cacheKey = "category.{$this->id}.translation.{$locale}";
        
        return cache()->remember($cacheKey, now()->addHours(24), function () use ($locale) {
            return $this->translations()->where('locale', $locale)->first();
        });
    }

    public function hasTranslation($locale = null): bool
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }
    
    public function getTranslatedName(): string
    {
        $cacheKey = "category.{$this->id}.name." . App::getLocale();
        
        return cache()->remember($cacheKey, now()->addHours(24), function () {
            $translation = $this->translation();
            return $translation ? $translation->name : $this->name;
        });
    }

    protected static function boot()
    {
        parent::boot();
        
        // Clear cache when category is updated or deleted
        static::updated(function ($category) {
            cache()->tags(['categories'])->flush();
        });

        static::deleted(function ($category) {
            cache()->tags(['categories'])->flush();
        });
    }
}
