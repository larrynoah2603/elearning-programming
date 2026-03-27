<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseHintView extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'hint_level',
        'viewed_at',
    ];

    protected $casts = [
        'hint_level' => 'integer',
        'viewed_at' => 'datetime',
    ];
}
