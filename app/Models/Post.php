<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function categories(): BelongsTo
    {
        return $this->category();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }
    
    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }
    
    public function hasTranslation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->translations()->where('locale', $locale)->exists();
    }
    
    public function getTranslatedTitle()
    {
        $translation = $this->translation();
        return $translation ? $translation->title : $this->title;
    }
    
    public function getTranslatedContent()
    {
        $translation = $this->translation();
        return $translation ? $translation->content : $this->content;
    }
    
    public function getTranslatedExcerpt()
    {
        $translation = $this->translation();
        return $translation ? $translation->excerpt : $this->excerpt;
    }
}
