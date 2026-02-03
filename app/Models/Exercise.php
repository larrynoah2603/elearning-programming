<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'difficulty',
        'access_level',
        'programming_language',
        'instructions',
        'starter_code',
        'solution_code',
        'hints',
        'points',
        'estimated_time',
        'user_id',
        'lesson_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points' => 'integer',
        'estimated_time' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($exercise) {
            if (empty($exercise->slug)) {
                $exercise->slug = Str::slug($exercise->title);
            }
        });

        static::updating(function ($exercise) {
            if ($exercise->isDirty('title') && empty($exercise->slug)) {
                $exercise->slug = Str::slug($exercise->title);
            }
        });
    }

    /**
     * Get the user that created the exercise.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson that owns the exercise.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the submissions for the exercise.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ExerciseSubmission::class);
    }

    /**
     * Scope a query to only include active exercises.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include free exercises.
     */
    public function scopeFree($query)
    {
        return $query->where('access_level', 'free');
    }

    /**
     * Scope a query to only include subscribed exercises.
     */
    public function scopeSubscribed($query)
    {
        return $query->where('access_level', 'subscribed');
    }

    /**
     * Scope a query to only include simple exercises.
     */
    public function scopeSimple($query)
    {
        return $query->where('difficulty', 'simple');
    }

    /**
     * Scope a query to only include complex exercises.
     */
    public function scopeComplex($query)
    {
        return $query->where('difficulty', 'complexe');
    }

    /**
     * Scope a query to filter by programming language.
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('programming_language', $language);
    }

    /**
     * Check if exercise is accessible by user.
     */
    public function isAccessibleBy(?User $user): bool
    {
        if ($this->access_level === 'free') {
            return true;
        }

        return $user && $user->isSubscribed();
    }

    /**
     * Get difficulty badge color.
     */
    public function getDifficultyBadgeColorAttribute(): string
    {
        return match($this->difficulty) {
            'simple' => 'success',
            'complexe' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get difficulty display name.
     */
    public function getDifficultyDisplayAttribute(): string
    {
        return match($this->difficulty) {
            'simple' => 'Simple',
            'complexe' => 'Complexe',
            default => $this->difficulty,
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
     * Get programming language display name.
     */
    public function getProgrammingLanguageDisplayAttribute(): string
    {
        return match($this->programming_language) {
            'python' => 'Python',
            'javascript' => 'JavaScript',
            'java' => 'Java',
            'cpp' => 'C++',
            'php' => 'PHP',
            'html_css' => 'HTML/CSS',
            'sql' => 'SQL',
            default => $this->programming_language,
        };
    }

    /**
     * Get programming language icon.
     */
    public function getProgrammingLanguageIconAttribute(): string
    {
        return match($this->programming_language) {
            'python' => 'fab fa-python',
            'javascript' => 'fab fa-js',
            'java' => 'fab fa-java',
            'php' => 'fab fa-php',
            'html_css' => 'fab fa-html5',
            'sql' => 'fas fa-database',
            default => 'fas fa-code',
        };
    }

    /**
     * Get estimated time display.
     */
    public function getEstimatedTimeDisplayAttribute(): ?string
    {
        if (!$this->estimated_time) {
            return null;
        }

        if ($this->estimated_time < 60) {
            return $this->estimated_time . ' min';
        }

        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;

        if ($minutes === 0) {
            return $hours . ' h';
        }

        return $hours . ' h ' . $minutes . ' min';
    }

    /**
     * Get user's submission for this exercise.
     */
    public function getUserSubmission(?User $user): ?ExerciseSubmission
    {
        if (!$user) {
            return null;
        }

        return $this->submissions()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Check if user has completed this exercise.
     */
    public function isCompletedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('status', 'reussi')
            ->exists();
    }

    /**
     * Get completion count.
     */
    public function getCompletionCountAttribute(): int
    {
        return $this->submissions()
            ->where('status', 'reussi')
            ->count();
    }

    /**
     * Get success rate percentage.
     */
    public function getSuccessRateAttribute(): int
    {
        $total = $this->submissions()->count();
        
        if ($total === 0) {
            return 0;
        }

        $successful = $this->submissions()
            ->where('status', 'reussi')
            ->count();

        return round(($successful / $total) * 100);
    }
}
