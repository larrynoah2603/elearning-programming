<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the lessons for the category.
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get lessons count.
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->lessons()->active()->count();
    }

    /**
     * Get exercises count through lessons.
     */
    public function getExercisesCountAttribute(): int
    {
        return $this->lessons()
            ->withCount(['exercises' => function ($query) {
                $query->active();
            }])
            ->get()
            ->sum('exercises_count');
    }

    /**
     * Get videos count through lessons.
     */
    public function getVideosCountAttribute(): int
    {
        return $this->lessons()
            ->withCount(['videos' => function ($query) {
                $query->active();
            }])
            ->get()
            ->sum('videos_count');
    }
}
