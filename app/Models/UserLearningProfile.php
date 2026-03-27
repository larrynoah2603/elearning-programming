<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLearningProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'goal',
        'minutes_per_day',
        'preferred_languages',
        'onboarding_completed_at',
    ];

    protected $casts = [
        'preferred_languages' => 'array',
        'minutes_per_day' => 'integer',
        'onboarding_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
