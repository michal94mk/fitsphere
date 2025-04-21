<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'locale',
        'name',
        'slug',
        'description'
    ];

    /**
     * Get the category that this translation belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
} 