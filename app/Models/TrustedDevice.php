<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustedDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'device_id',
        'device_type',
        'platform',
        'browser',
        'ip_address',
        'location',
        'last_login_at',
        'expires_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if device is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope for active devices
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for user devices
     */
    public function scopeForUser($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}