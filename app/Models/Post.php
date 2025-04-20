<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'slug', 
        'excerpt', 
        'content', 
        'image', 
        'status', 
        'category_id', 
        'view_count'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function categories()
    {
        return $this->category();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }
    
    /**
     * Get all translations for this post
     */
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }
    
    /**
     * Get translation for the specified locale
     * 
     * @param string|null $locale
     * @return \App\Models\PostTranslation|null
     */
    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }
    
    /**
     * Check if the post has a translation for the specified locale
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
     * Get translated title in current locale or fallback to original
     * 
     * @return string
     */
    public function getTranslatedTitle()
    {
        $translation = $this->translation();
        return $translation ? $translation->title : $this->title;
    }
    
    /**
     * Get translated content in current locale or fallback to original
     * 
     * @return string
     */
    public function getTranslatedContent()
    {
        $translation = $this->translation();
        return $translation ? $translation->content : $this->content;
    }
    
    /**
     * Get translated excerpt in current locale or fallback to original
     * 
     * @return string
     */
    public function getTranslatedExcerpt()
    {
        $translation = $this->translation();
        return $translation ? $translation->excerpt : $this->excerpt;
    }
}
