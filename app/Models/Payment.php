<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'operator',
        'phone_number',
        'crypto_type',
        'network',
        'crypto_amount',
        'receiving_address',
        'transaction_hash',
        'bank_name',
        'reference',
        'confirmed_at',
        'verified_at',
        'verified_by',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'crypto_amount' => 'decimal:8',
        'confirmed_at' => 'datetime',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user that made the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get subscription associated with this payment.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Get payment method display name.
     */
    public function getPaymentMethodDisplayAttribute(): string
    {
        $methods = [
            'mobile_money' => 'Mobile Money',
            'cryptocurrency' => 'Cryptomonnaie',
            'bank_transfer' => 'Virement Bancaire',
            'card' => 'Carte Bancaire',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'completed', 'confirmed' => 'success',
            'pending' => 'warning',
            'failed', 'cancelled' => 'danger',
            'expired' => 'secondary',
            default => 'info',
        };
    }

    /**
     * Get status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pending' => 'En attente',
            'completed' => 'Complété',
            'confirmed' => 'Confirmé',
            'failed' => 'Échoué',
            'cancelled' => 'Annulé',
            'expired' => 'Expiré',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if payment can be retried.
     */
    public function canRetry(): bool
    {
        return in_array($this->status, ['failed', 'expired']) && 
               !$this->isExpired();
    }
}