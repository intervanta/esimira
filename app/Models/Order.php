<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WalletTransaction;
class Order extends Model
{
    use HasFactory;

    // Order Status Constants
    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_REFUNDED = 5;
    const STATUS_FAILED = 6;

    // Payment Status Constants
    const PAYMENT_PENDING = 0;
    const PAYMENT_PAID = 1;
    const PAYMENT_FAILED = 2;
    const PAYMENT_REFUNDED = 3;
    const PAYMENT_PARTIALLY_REFUNDED = 4;

    protected $fillable = [
        'order_number',
        'invoice_number',
        'customer_id',
        'bundle_id',
        'refill_id',
        'plan_amount',
        'convenience_fee',
        'gst_amount',
        'total_amount',
        'discount_amount',
        'miravault_used',
        'final_amount',
        'currency',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'status',
        'payment_status',
        'paid_at',
        'cancelled_at',
        'completed_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status Methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }
    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
    public function activation()
    {
        return $this->belongsTo(Activation::class);
    }
    public function refill()
    {
        return $this->belongsTo(Refill::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    // Business Logic Methods
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => self::PAYMENT_PAID,
            'status' => self::STATUS_CONFIRMED,
            'paid_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'payment_status' => self::PAYMENT_FAILED,
        ]);
    }

    public function markAsCancelled()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_FAILED => 'Failed',
            default => 'Unknown',
        };
    }

    public function getPaymentStatusTextAttribute()
    {
        return match ($this->payment_status) {
            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_PAID => 'Paid',
            self::PAYMENT_FAILED => 'Failed',
            self::PAYMENT_REFUNDED => 'Refunded',
            self::PAYMENT_PARTIALLY_REFUNDED => 'Partially Refunded',
            default => 'Unknown',
        };
    }

    public function walletTransaction()
    {
        return $this->hasOne(WalletTransaction::class, 'order_id');
    }

    public function amount()
    {
        return $this->hasOne(OrderAmount::class);
    }

}
