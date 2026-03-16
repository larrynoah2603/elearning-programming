<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationUserProgress extends Model
{
    use HasFactory;

    protected $table = 'formation_user_progress';

    protected $fillable = [
        'user_id',
        'formation_id',
        'formation_module_id',
        'progress_percentage',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function formationModule(): BelongsTo
    {
        return $this->belongsTo(FormationModule::class, 'formation_module_id');
    }

    public function markCompleted()
    {
        $this->update([
            'is_completed' => true,
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);
    }
}
