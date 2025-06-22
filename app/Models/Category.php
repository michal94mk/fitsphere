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
        return $this->translations()->where('locale', $locale)->first();
    }

    public function hasTranslation($locale = null): bool
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }
    
    public function getTranslatedName(): string
    {
        $translation = $this->translation();
        return $translation ? $translation->name : $this->name;
    }

    protected static function boot()
    {
        parent::boot();
    }
}
