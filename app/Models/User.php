<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_expires_at' => 'date',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has free account
     */
    public function isFree(): bool
    {
        return $this->role === 'free';
    }

    /**
     * Check if user has subscribed account
     */
    public function isSubscribed(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        
        if ($this->role === 'subscribed' && $this->subscription_expires_at) {
            return $this->subscription_expires_at->isFuture();
        }
        
        return false;
    }

    /**
     * Check if subscription is expired
     */
    public function isSubscriptionExpired(): bool
    {
        if ($this->role === 'subscribed' && $this->subscription_expires_at) {
            return $this->subscription_expires_at->isPast();
        }
        
        return false;
    }

    /**
     * Get subscription status text
     */
    public function getSubscriptionStatusAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Administrateur';
        }
        
        if ($this->isSubscribed()) {
            $daysLeft = now()->diffInDays($this->subscription_expires_at);
            return "Abonné (expire dans {$daysLeft} jours)";
        }
        
        if ($this->isSubscriptionExpired()) {
            return 'Abonnement expiré';
        }
        
        return 'Gratuit';
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'admin' => 'danger',
            'subscribed' => 'success',
            'free' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get exercises created by user
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Get lessons created by user
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get videos created by user
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get exercise submissions
     */
    public function exerciseSubmissions()
    {
        return $this->hasMany(ExerciseSubmission::class);
    }

    /**
     * Get video progress
     */
    public function videoProgress()
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Get completed exercises count
     */
    public function getCompletedExercisesCountAttribute(): int
    {
        return $this->exerciseSubmissions()
            ->where('status', 'reussi')
            ->count();
    }

    /**
     * Get total points earned
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->exerciseSubmissions()
            ->where('status', 'reussi')
            ->sum('score') ?? 0;
    }

    /**
     * Get watched videos count
     */
    public function getWatchedVideosCountAttribute(): int
    {
        return $this->videoProgress()
            ->where('is_completed', true)
            ->count();
    }

    /**
     * Upgrade user to subscribed
     */
    public function upgradeToSubscribed(int $days = 30): void
    {
        $this->update([
            'role' => 'subscribed',
            'subscription_expires_at' => now()->addDays($days),
        ]);
    }

    /**
     * Downgrade user to free
     */
    public function downgradeToFree(): void
    {
        $this->update([
            'role' => 'free',
            'subscription_expires_at' => null,
        ]);
    }

    /**
     * Extend subscription
     */
    public function extendSubscription(int $days): void
    {
        $currentExpiry = $this->subscription_expires_at ?? now();
        
        if ($currentExpiry->isPast()) {
            $currentExpiry = now();
        }
        
        $this->update([
            'subscription_expires_at' => $currentExpiry->addDays($days),
        ]);
    }
}
