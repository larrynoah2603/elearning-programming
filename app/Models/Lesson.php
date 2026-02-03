<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'level',
        'access_level',
        'pdf_file',
        'page_count',
        'user_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'page_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });

        static::updating(function ($lesson) {
            if ($lesson->isDirty('title') && empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    /**
     * Get the user that created the lesson.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercises for the lesson.
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Get the videos for the lesson.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the categories for the lesson.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Scope a query to only include active lessons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include free lessons.
     */
    public function scopeFree($query)
    {
        return $query->where('access_level', 'free');
    }

    /**
     * Scope a query to only include subscribed lessons.
     */
    public function scopeSubscribed($query)
    {
        return $query->where('access_level', 'subscribed');
    }

    /**
     * Scope a query to filter by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Check if lesson is accessible by user.
     */
    public function isAccessibleBy(?User $user): bool
    {
        if ($this->access_level === 'free') {
            return true;
        }

        return $user && $user->isSubscribed();
    }

    /**
     * Get level badge color.
     */
    public function getLevelBadgeColorAttribute(): string
    {
        return match($this->level) {
            'debutant' => 'success',
            'intermediaire' => 'warning',
            'avance' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get level display name.
     */
    public function getLevelDisplayAttribute(): string
    {
        return match($this->level) {
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            default => $this->level,
        };
    }

    /**
     * Get access level display name.
     */
    public function getAccessLevelDisplayAttribute(): string
    {
        return match($this->access_level) {
            'free' => 'Gratuit',
            'subscribed' => 'Abonnement',
            default => $this->access_level,
        };
    }

    /**
     * Get PDF file URL.
     */
    public function getPdfUrlAttribute(): string
    {
        return asset('storage/' . $this->pdf_file);
    }

    /**
     * Get exercises count.
     */
    public function getExercisesCountAttribute(): int
    {
        return $this->exercises()->active()->count();
    }

    /**
     * Get videos count.
     */
    public function getVideosCountAttribute(): int
    {
        return $this->videos()->active()->count();
    }
}
