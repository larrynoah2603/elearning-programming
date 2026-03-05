<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formation) {
            if (empty($formation->slug)) {
                $formation->slug = Str::slug($formation->title);
            }
        });
    }

    public function modules(): HasMany
    {
        return $this->hasMany(FormationModule::class)->orderBy('order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(FormationEnrollment::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'formation_enrollments')
            ->withPivot(['amount_paid', 'status', 'payment_method', 'paid_at']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getLevelDisplayAttribute(): string
    {
        return match ($this->level) {
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            default => ucfirst($this->level),
        };
    }
}
