<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\TrustedDeviceService;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'country_code',
        'contact_number',
        'password',
        'referral_code',
        'promotional_emails',
        'email_verified_at',
        'locale',
        'currency', // recommended to store user's preferred currency
        'referral_credits', // if you store referral earnings separately
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'promotional_emails' => 'boolean',
        'referral_credits' => 'decimal:2',
    ];

    // Relationships
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }

    public function activeTrustedDevices()
    {
        return $this->trustedDevices()->active();
    }

    public function isCurrentDeviceTrusted(): bool
    {
        $deviceId = app(TrustedDeviceService::class)->generateDeviceId();
        return $this->activeTrustedDevices()
            ->where('device_id', $deviceId)
            ->exists();
    }

    public static function generateReferralCode($name)
    {
        $prefix = strtoupper(Str::substr(Str::slug($name), 0, 6));
        $lastUser = static::orderByDesc('id')->first();
        $nextNumber = $lastUser ? $lastUser->id + 1 : 1;
        $number = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        return $prefix . $number;
    }

    public function referredUsers()
    {
        return $this->hasMany(Customer::class, 'referred_by');
    }

    public function getReferralLinkAttribute()
    {
        return url('/ref?code=' . $this->referral_code);
    }

    public function getReferralEarningsAttribute()
    {
        return $this->attributes['referral_credits'] ?? 0;
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'customer_id');
    }

    // Wallet Balance (in user's current currency)
    public function getWalletBalanceAttribute()
    {
        $credits = $this->walletTransactions()
            ->where('type', 'credit')
            ->sum('amount');

        $debits = $this->walletTransactions()
            ->where('type', 'debit')
            ->sum('amount');

        return max(0, $credits - $debits); // never negative
    }

    // Total Credits Ever Earned
    public function getTotalCreditsEarnedAttribute()
    {
        return $this->walletTransactions()
            ->where('type', 'credit')
            ->sum('amount');
    }

    // Current Currency Symbol (Session > DB > Fallback)
    public function getCurrencySymbolAttribute()
    {
        return session('currency_symbol') 
            ?? $this->currency 
            ?? '$';
    }
}