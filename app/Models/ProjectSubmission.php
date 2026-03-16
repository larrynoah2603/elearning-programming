<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSubmission extends Model
{
    protected $table = 'project_submissions';

    protected $fillable = [
        'user_id',
        'final_project_id',
        'submission_text',
        'repository_url',
        'demo_url',
        'files',
        'score',
        'status',
        'feedback',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'files' => 'json',
        'score' => 'int',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Relation avec User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec FinalProject
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(FinalProject::class, 'final_project_id');
    }

    /**
     * Relation avec User (reviewer)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Vérifier si la soumission est approuvée
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Vérifier si la soumission passe le score minimum
     */
    public function passesMinimumScore(): bool
    {
        return $this->score >= $this->project->passing_score;
    }

    /**
     * Marquer comme approuvée
     */
    public function approve(int $score, ?string $feedback = null, ?int $reviewerId = null): void
    {
        $this->update([
            'status' => 'accepted',
            'score' => $score,
            'feedback' => $feedback,
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Marquer comme rejetée
     */
    public function reject(?string $feedback = null, ?int $reviewerId = null): void
    {
        $this->update([
            'status' => 'rejected',
            'feedback' => $feedback,
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'reviewed_at' => now(),
        ]);
    }
}