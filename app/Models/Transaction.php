<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Transaction Status Constants
    const STATUS_INITIATED = 0;
    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_FAILED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_REFUNDED = 5;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'customer_id',
        'gateway',
        'gateway_transaction_id',
        'gateway_order_id',
        'amount',
        'convenience_fee',
        'gst_on_fee',
        'total_amount',
        'currency',
        'exchange_rate',
        'status',
        'failure_reason',
        'payment_method',
        'wallet_type',
        'gateway_request',
        'gateway_response',
        'webhook_data',
        'gateway_signature',
        'ip_address',
        'user_agent',
        'initiated_at',
        'processed_at',
        'completed_at',
        'failed_at'
    ];

    protected $casts = [
        'gateway_request' => 'array',
        'gateway_response' => 'array',
        'webhook_data' => 'array',
        'exchange_rate' => 'decimal:6',
        'initiated_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Status Methods
    public function isInitiated()
    {
        return $this->status === self::STATUS_INITIATED;
    }
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeByGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    // Business Logic Methods
    public function markAsCompleted($gatewayResponse = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'gateway_response' => $gatewayResponse,
        ]);

        // Update order status
        $this->order->markAsPaid();
    }

    public function markAsFailed($failureReason = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failure_reason' => $failureReason,
            'failed_at' => now(),
            'gateway_response' => $gatewayResponse,
        ]);

        // Update order status
        $this->order->markAsFailed($failureReason);
    }

    public function markAsRefunded($refundData = null)
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'gateway_response' => $refundData,
        ]);
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_INITIATED => 'Initiated',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
            default => 'Unknown',
        };
    }

    public function getGatewayNameAttribute()
    {
        return match ($this->gateway) {
            'razorpay' => 'Razorpay',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'bank_transfer' => 'Bank Transfer',
            'wallet' => 'Wallet',
            default => ucfirst($this->gateway),
        };
    }
}
