<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'exercise_id',
        'title',
        'body',
        'is_resolved',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ForumTag::class, 'forum_thread_tag', 'forum_thread_id', 'forum_tag_id');
    }
}
