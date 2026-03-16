<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'user_id',
        'formation_id',
        'certificate_number',
        'file_path',
        'issued_at',
        'expires_at',
        'metadata',
        'verification_token',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    // Méthodes
    public static function generateCertificateNumber(): string
    {
        return 'CERT-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . rand(10000, 99999);
    }

    public static function generateVerificationToken(): string
    {
        return hash('sha256', Str::random(32) . time());
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->isExpired() && file_exists(storage_path($this->file_path));
    }

    public function getPublicUrl(): string
    {
        return route('certificates.verify', $this->verification_token);
    }

    public function getDownloadUrl(): string
    {
        return route('certificates.download', $this->id);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->whereNull('expires_at')
            ->orWhere('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }
}
