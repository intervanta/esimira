<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    // Coupon Type Constants
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
    const TYPE_FREE = 'free';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_bundles',
        'applicable_users',
        'description'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'applicable_bundles' => 'array',
        'applicable_users' => 'array',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if (now()->lt($this->valid_from) || now()->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount, $bundleId = null, $userId = null)
    {
        if (!$this->isValid()) {
            return 0;
        }

        // Check if coupon is applicable to this bundle
        if ($this->applicable_bundles && !in_array($bundleId, $this->applicable_bundles)) {
            return 0;
        }

        // Check if coupon is applicable to this user
        if ($this->applicable_users && !in_array($userId, $this->applicable_users)) {
            return 0;
        }

        // Check minimum order amount
        if ($this->min_order_amount && $amount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discount = $amount * ($this->value / 100);
                if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                    $discount = $this->max_discount_amount;
                }
                break;
            case self::TYPE_FIXED:
                $discount = min($this->value, $amount);
                break;
            case self::TYPE_FREE:
                $discount = $amount;
                break;
        }

        return min($discount, $amount);
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}