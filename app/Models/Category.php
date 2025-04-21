<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all translations for this category
     */
    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }
    
    /**
     * Get translation for the specified locale
     * 
     * @param string|null $locale
     * @return \App\Models\CategoryTranslation|null
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }
    
    /**
     * Check if the category has a translation for the specified locale
     * 
     * @param string|null $locale
     * @return bool
     */
    public function hasTranslation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }
    
    /**
     * Get translated name in current locale or fallback to original
     * 
     * @return string
     */
    public function getTranslatedName()
    {
        $translation = $this->translation();
        return $translation ? $translation->name : $this->name;
    }
    
    /**
     * Get translated description in current locale or fallback to original
     * 
     * @return string|null
     */
    public function getTranslatedDescription()
    {
        $translation = $this->translation();
        return $translation ? $translation->description : null;
    }
}
