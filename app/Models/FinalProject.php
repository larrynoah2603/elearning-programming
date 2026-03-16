<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalProject extends Model
{
    protected $table = 'final_projects';

    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'requirements',
        'evaluation_criteria',
        'max_score',
        'passing_score',
    ];

    protected $casts = [
        'requirements' => 'json',
        'evaluation_criteria' => 'json',
        'max_score' => 'int',
        'passing_score' => 'int',
    ];

    /**
     * Relation avec Formation
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Relation avec ProjectSubmission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class);
    }

    /**
     * Obtenir les soumissions approuvées
     */
    public function approvedSubmissions(): HasMany
    {
        return $this->submissions()->where('status', 'accepted');
    }

    /**
     * Vérifier si un utilisateur a soumis ce projet
     */
    public function hasUserSubmitted(int $userId): bool
    {
        return $this->submissions()->where('user_id', $userId)->exists();
    }

    /**
     * Obtenir la soumission d'un utilisateur spécifique
     */
    public function getUserSubmission(int $userId): ?ProjectSubmission
    {
        return $this->submissions()->where('user_id', $userId)->latest()->first();
    }
}