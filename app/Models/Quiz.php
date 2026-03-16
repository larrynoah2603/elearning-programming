<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'duration_minutes',
        'passing_score',
        'max_attempts',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'passing_score' => 'integer',
    ];

    // Relations
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(QuizSubmission::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Méthodes
    public function getAverageScore(?int $userId = null)
    {
        $query = $this->submissions()->where('status', '!=', 'pending');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->avg('score') ?? 0;
    }

    public function getPassRate()
    {
        $total = $this->submissions()->where('status', '!=', 'pending')->count();
        $passed = $this->submissions()->where('status', 'passed')->count();

        return $total > 0 ? ($passed / $total) * 100 : 0;
    }

    public function hasUserPassed(int $userId): bool
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->where('status', 'passed')
            ->exists();
    }

    public function getUserAttempts(int $userId): int
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->max('attempt_number') ?? 0;
    }

    public function canUserRetake(int $userId): bool
    {
        return $this->getUserAttempts($userId) < $this->max_attempts;
    }
}
