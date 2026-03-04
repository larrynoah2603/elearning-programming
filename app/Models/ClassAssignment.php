<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'content_type',
        'content_id',
        'title',
        'instructions',
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function content(): Exercise|Lesson|null
    {
        if ($this->content_type === 'exercise') {
            return Exercise::find($this->content_id);
        }

        if ($this->content_type === 'lesson') {
            return Lesson::find($this->content_id);
        }

        return null;
    }
}
