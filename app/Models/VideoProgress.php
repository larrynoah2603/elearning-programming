<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected $fillable = [
        'user_id',
        'video_id',
        'current_time',
        'watched_duration',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'current_time' => 'integer',
        'watched_duration' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($progress) {
            // Ensure watched_duration doesn't exceed video duration
            if ($progress->video && $progress->video->duration) {
                $progress->watched_duration = min($progress->watched_duration, $progress->video->duration);
            }
        });
    }

    /**
     * Get the user that watched the video.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that was watched.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Update progress.
     */
    public function updateProgress(int $currentTime, int $watchedDuration): void
    {
        $this->current_time = $currentTime;
        
        // Load video relation if not loaded
        if (!$this->relationLoaded('video')) {
            $this->load('video');
        }
        
        // Only increment watched duration if moving forward
        if ($watchedDuration > $this->watched_duration) {
            $this->watched_duration = $watchedDuration;
        }

        // Check if video is completed (90% watched)
        if ($this->video && $this->video->duration > 0) {
            $threshold = $this->video->duration * 0.9;
            
            if ($this->watched_duration >= $threshold && !$this->is_completed) {
                $this->is_completed = true;
                $this->completed_at = now();
            }
        }

        $this->save();
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentageAttribute(): int
    {
        if (!$this->relationLoaded('video')) {
            $this->load('video');
        }
        
        if (!$this->video || !$this->video->duration || $this->video->duration <= 0) {
            return 0;
        }

        return min(100, round(($this->watched_duration / $this->video->duration) * 100));
    }

    /**
     * Get current time display.
     */
    public function getCurrentTimeDisplayAttribute(): string
    {
        return $this->formatDuration($this->current_time);
    }

    /**
     * Get watched duration display.
     */
    public function getWatchedDurationDisplayAttribute(): string
    {
        return $this->formatDuration($this->watched_duration);
    }

    /**
     * Format duration in seconds to MM:SS or HH:MM:SS.
     */
    private function formatDuration(int $seconds): string
    {
        if ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $secs = $seconds % 60;
            return sprintf('%d:%02d', $minutes, $secs);
        }
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
    }
}