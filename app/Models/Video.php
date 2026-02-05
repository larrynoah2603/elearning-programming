<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'video_file',
        'thumbnail',
        'duration',
        'level',
        'access_level',
        'is_active',
        'order',
        'user_id',
        'lesson_id',
        'views',
    ];

    protected $casts = [
        'duration' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
        'views' => 'integer',
    ];

    protected $attributes = [
        'views' => 0,
        'order' => 0,
        'is_active' => true,
    ];

    // Ajoutez ces accesseurs pour l'affichage dans les vues
    protected $appends = [
        'video_url',
        'thumbnail_url',
        'level_display',
        'level_badge_color',
        'duration_display',
        'views_count',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->slug)) {
                $video->slug = \Illuminate\Support\Str::slug($video->title);
            }
            if (empty($video->views)) {
                $video->views = 0;
            }
        });
    }

    /**
     * Get the user that uploaded the video.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson associated with the video.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the progress records for this video.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Get user's progress for this video.
     */
    public function getUserProgress(?User $user): ?VideoProgress
    {
        if (!$user) {
            return null;
        }

        return $this->progress()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Scope a query to only include active videos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by level.
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope a query to filter by access level.
     */
    public function scopeByAccessLevel($query, string $accessLevel)
    {
        return $query->where('access_level', $accessLevel);
    }

    /**
     * Check if the video is accessible by a user.
     */
    public function isAccessibleBy(?User $user): bool
    {
        // Free videos are accessible to everyone
        if ($this->access_level === 'free') {
            return true;
        }

        // Premium videos require authentication and subscription
        if (!$user) {
            return false;
        }

        return $user->isSubscribed();
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get video URL - CORRIGÉ POUR VOTRE ENVIRONNEMENT
     */
    public function getVideoUrlAttribute(): ?string
    {
        if (!$this->video_file) {
            return null;
        }

        if (filter_var($this->video_file, FILTER_VALIDATE_URL)) {
            return $this->video_file;
        }

        return route('videos.stream', $this->id);
    }

    /**
     * Get thumbnail URL - CORRIGÉ POUR VOTRE ENVIRONNEMENT
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        $defaultThumbnail = asset('images/default-video-thumbnail.svg');

        if (!$this->thumbnail) {
            return $defaultThumbnail;
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        if (!Storage::disk('public')->exists($this->thumbnail)) {
            return $defaultThumbnail;
        }

        return Storage::url($this->thumbnail);
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
            default => ucfirst($this->level),
        };
    }

    /**
     * Get level badge color.
     */
    public function getLevelBadgeColorAttribute(): string
    {
        return match($this->level) {
            'debutant' => 'green',
            'intermediaire' => 'yellow',
            'avance' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get formatted duration.
     */
    public function getDurationDisplayAttribute(): ?string
    {
        if (!$this->duration) {
            return null;
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Alias pour formatted_duration (pour compatibilité avec votre vue)
     */
    public function getFormattedDurationAttribute(): ?string
    {
        return $this->getDurationDisplayAttribute();
    }

    /**
     * Alias pour views_count (pour compatibilité avec votre vue)
     */
    public function getViewsCountAttribute(): int
    {
        return $this->views ?? 0;
    }

    /**
     * Get route key name.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
