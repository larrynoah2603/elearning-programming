<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class QuizSubmission extends Model
{
    use HasFactory;

    protected $table = 'quiz_submissions';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'status',
        'answers',
        'attempt_number',
        'submitted_at',
        'graded_at',
        'grader_feedback',
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Accesseurs et mutateurs
    public function isPassed(): bool
    {
        return $this->score >= $this->quiz->passing_score;
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed' || !$this->isPassed();
    }

    public function canRetake(): bool
    {
        return $this->quiz->canUserRetake($this->user_id);
    }

    public function getFormattedScore(): string
    {
        return number_format($this->score, 1, ',', ' ') . '%';
    }

    public function getPassingScore(): int
    {
        return $this->quiz->passing_score;
    }

    // Scopes
    public function scopeLatest($query)
    {
        return $query->orderBy('submitted_at', 'desc');
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
