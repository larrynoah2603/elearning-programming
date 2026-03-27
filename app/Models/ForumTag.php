<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ForumTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function threads(): BelongsToMany
    {
        return $this->belongsToMany(ForumThread::class, 'forum_thread_tag', 'forum_tag_id', 'forum_thread_id');
    }
}
