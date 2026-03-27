<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseHint extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'level',
        'content',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
