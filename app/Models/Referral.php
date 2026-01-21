<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',        
        'referred_user_id',   
        'referral_code',  
        'referral_source',    
        'event',              
        'reward_amount',     
        'meta',      
        'status'         
    ];

    protected $casts = [
        'meta' => 'array',
        'reward_amount' => 'decimal:2',
    ];

    /**
     * The user who referred (referrer)
     */
    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    /**
     * The user who was referred (new user)
     */
    public function referredUser()
    {
        return $this->belongsTo(Customer::class, 'referred_user_id');
    }

    /**
     * Helper method to log referral activity
     */
    public static function logEvent($referrerId, $referredUserId, $code, $event, $amount = 0, $meta = [])
    {
        return self::create([
            'referrer_id'      => $referrerId,
            'referred_user_id' => $referredUserId,
            'referral_code'    => $code,
            'event'            => $event,
            'reward_amount'    => $amount,
            'meta'             => $meta,
        ]);
    }
}
