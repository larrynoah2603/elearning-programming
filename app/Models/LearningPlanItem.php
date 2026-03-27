<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_plan_id',
        'type',
        'target_id',
        'title',
        'url',
        'estimated_minutes',
        'position',
        'is_done',
        'done_at',
    ];

    protected $casts = [
        'estimated_minutes' => 'integer',
        'position' => 'integer',
        'is_done' => 'boolean',
        'done_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LearningPlan::class, 'learning_plan_id');
    }
}
