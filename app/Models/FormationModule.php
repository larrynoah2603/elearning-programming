<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'title',
        'description',
        'duration_minutes',
        'order',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'order' => 'integer',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'formation_module_lesson')
            ->orderBy('order');
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'formation_module_video')
            ->orderBy('order');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'formation_module_exercise')
            ->orderBy('order');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(FormationUserProgress::class);
    }

    public function getUserProgress($userId)
    {
        return $this->userProgress()
            ->where('user_id', $userId)
            ->first();
    }
}
