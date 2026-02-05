<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'submitted_code',
        'status',
        'score',
        'feedback',
        'attempts',
        'submitted_at',
        'corrected_at',
        'corrected_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'corrected_at' => 'datetime',
        'attempts' => 'integer',
        'score' => 'integer',
    ];

    /**
     * Get the user that made the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercise that was submitted.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Get the user that corrected the submission.
     */
    public function corrector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }

    /**
     * Scope a query to only include pending submissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'soumis');
    }

    /**
     * Scope a query to only include corrected submissions.
     */
    public function scopeCorrected($query)
    {
        return $query->whereIn('status', ['corrige', 'reussi', 'echoue']);
    }

    /**
     * Scope a query to only include successful submissions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'reussi');
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'en_cours' => 'info',
            'soumis' => 'warning',
            'corrige' => 'secondary',
            'reussi' => 'success',
            'echoue' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'en_cours' => 'En cours',
            'soumis' => 'Soumis',
            'corrige' => 'Corrigé',
            'reussi' => 'Réussi',
            'echoue' => 'Échoué',
            default => $this->status,
        };
    }

    /**
     * Submit the exercise.
     */
    public function submit(string $code): void
    {
        $this->fill([
            'submitted_code' => $code,
            'status' => 'soumis',
            'submitted_at' => now(),
            'attempts' => ($this->attempts ?? 0) + 1,
        ]);

        $this->save();
    }

    /**
     * Mark as corrected.
     */
    public function markAsCorrected(int $score, ?string $feedback = null, ?int $correctedBy = null): void
    {
        $status = $score >= 50 ? 'reussi' : 'echoue';

        $this->update([
            'score' => $score,
            'feedback' => $feedback,
            'status' => $status,
            'corrected_at' => now(),
            'corrected_by' => $correctedBy,
        ]);
    }

    /**
     * Check if submission is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'reussi';
    }

    /**
     * Check if submission is pending correction.
     */
    public function isPendingCorrection(): bool
    {
        return $this->status === 'soumis';
    }

    /**
     * Get score percentage display.
     */
    public function getScorePercentageAttribute(): string
    {
        if ($this->score === null) {
            return '-';
        }

        return $this->score . '%';
    }

    /**
     * Get time spent on exercise.
     */
    public function getTimeSpentAttribute(): ?string
    {
        if (!$this->submitted_at) {
            return null;
        }

        $diff = $this->created_at->diff($this->submitted_at);

        if ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'min';
        }

        if ($diff->i > 0) {
            return $diff->i . 'min ' . $diff->s . 's';
        }

        return $diff->s . 's';
    }
}
