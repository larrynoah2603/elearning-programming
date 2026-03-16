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
        'school_name',
        'class_name',
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
     * Get normalized role value.
     */
    public function normalizedRole(): string
    {
        return strtolower(trim((string) $this->role));
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->normalizedRole(), ['admin', 'administrateur'], true)
            || $this->isConfiguredAdminEmail();
    }

    /**
     * Check if user is teacher.
     */
    public function isTeacher(): bool
    {
        return in_array($this->normalizedRole(), ['teacher', 'enseignant', 'professeur'], true);
    }

    /**
     * Check if user email is in configured admin list.
     */
    private function isConfiguredAdminEmail(): bool
    {
        $admins = config('app.admin_emails', []);

        if (!is_array($admins)) {
            return false;
        }

        return in_array(strtolower(trim((string) $this->email)), $admins, true);
    }

    /**
     * Check if user has free account
     */
    public function isFree(): bool
    {
        return $this->normalizedRole() === 'free';
    }

    /**
     * Check if user has subscribed account
     */
    public function isSubscribed(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if ($this->normalizedRole() === 'subscribed' && $this->subscription_expires_at) {
            return $this->subscription_expires_at->isFuture();
        }
        
        return false;
    }

    /**
     * Check if subscription is expired
     */
    public function isSubscriptionExpired(): bool
    {
        if ($this->normalizedRole() === 'subscribed' && $this->subscription_expires_at) {
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
        return match($this->normalizedRole()) {
            'admin', 'administrateur' => 'danger',
            'subscribed' => 'success',
            'teacher', 'enseignant', 'professeur' => 'warning',
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
     * Groups created by teacher.
     */
    public function teachingGroups()
    {
        return $this->hasMany(ClassGroup::class, 'teacher_id');
    }

    /**
     * Groups where student is enrolled.
     */
    public function studentGroups()
    {
        return $this->belongsToMany(ClassGroup::class, 'class_group_student', 'student_id', 'class_group_id')
            ->withTimestamps();
    }

    /**
     * Paid formations purchased by the user.
     */
    public function paidFormations()
    {
        return $this->belongsToMany(Formation::class, 'formation_enrollments')
            ->withPivot(['amount_paid', 'status', 'payment_method', 'paid_at'])
            ->withTimestamps();
    }

    /**
     * Formation enrollment records.
     */
    public function formationEnrollments()
    {
        return $this->hasMany(FormationEnrollment::class);
    }

    /**
     * Check if the user has already purchased a formation.
     */
    public function hasPurchasedFormation(int $formationId): bool
    {
        return $this->formationEnrollments()
            ->where('formation_id', $formationId)
            ->where('status', 'paid')
            ->exists();
    }

    /**
     * Quiz submissions by user
     */
    public function quizSubmissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }

    /**
     * Certificates earned by user
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Project submissions by user
     */
    public function projectSubmissions()
    {
        return $this->hasMany(ProjectSubmission::class);
    }

    /**
     * Get completed exercises count
     */
    public function getCompletedExercisesCountAttribute(): int
    {
        return $this->exerciseSubmissions()
            ->where('status', 'reussi')
            ->distinct()
            ->count('exercise_id');
    }

    /**
     * Get total points earned
     */
    public function getTotalPointsAttribute(): int
    {
        return (int) (Exercise::query()
            ->whereHas('submissions', function ($query) {
                $query->where('user_id', $this->id)
                    ->where('status', 'reussi');
            })
            ->sum('points') ?? 0);
    }

    /**
     * Get watched videos count
     */
    public function getWatchedVideosCountAttribute(): int
    {
        return $this->videoProgress()
            ->where('is_completed', true)
            ->distinct()
            ->count('video_id');
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
