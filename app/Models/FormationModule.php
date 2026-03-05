<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
