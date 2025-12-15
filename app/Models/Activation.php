<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    use HasFactory;

     protected $fillable = [
        // Relations
        'bundle_id',
        'bundle_name',
        'refill_id',
        'is_refill',
        'order_id',
        'customer_id',

        // SIM / KeepGo data
        'iccid',
        'msisdn',
        'smdp_plus',
        'activation_code',
        'lpa_code',
        'qr_code_url',

        // Device
        'device_model',
        'device_os',

        // Status & lifecycle
        'status',
        'notes',
        'activated_at',
        'deactivation_date',

        // Usage & refill
        'is_auto_refill',
        'allowed_usage_mb',
        'remaining_usage_mb',
        'remaining_days',
    ];

    protected $casts = [
        'is_refill'        => 'boolean',
        'is_auto_refill'   => 'boolean',
        'activated_at'     => 'datetime',
        'deactivation_date'=> 'datetime',
        'remaining_usage_mb' => 'integer',
        'allowed_usage_mb'   => 'integer',
        'remaining_days'     => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
    public function refill()
    {
        return $this->belongsTo(Refill::class);
    }
}
