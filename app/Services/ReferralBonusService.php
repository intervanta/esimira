<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReferralBonusService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Process referral bonus on first purchase
     */
    public function processFirstPurchaseBonus($customerId, $orderAmount = null)
    {
        try {
            // Find pending referrals for this customer
            $referral = Referral::where('referred_user_id', $customerId)
                ->where('status', 'pending')
                ->where('event', 'signup')
                ->first();

            if (!$referral) {
                Log::info('No pending referral found for customer', ['customer_id' => $customerId]);
                return false;
            }

            // Check if this is the first purchase (you might want additional logic here)
            $isFirstPurchase = $this->isFirstPurchase($customerId);
            
            switch ($isFirstPurchase) {
                case true:
                    // Calculate bonus amount
                    $bonusAmount = $this->calculateBonusAmount($orderAmount);
                    
                    // Update referral record
                    $referral->update([
                        'status' => 'completed',
                        'reward_amount' => $bonusAmount,
                        'event' => 'first_purchase',
                        'meta' => json_encode(array_merge(
                            json_decode($referral->meta, true) ?? [],
                            [
                                'first_purchase_date' => Carbon::now()->toDateTimeString(),
                                'order_amount' => $orderAmount,
                                'bonus_awarded_at' => Carbon::now()->toDateTimeString()
                            ]
                        ))
                    ]);

                    // Add bonus to referrer's wallet
                    $this->walletService->addReferralBonus(
                        $referral->referrer_id, 
                        $customerId, 
                        $bonusAmount
                    );

                    Log::info('Referral bonus processed on first purchase', [
                        'referrer_id' => $referral->referrer_id,
                        'referred_user_id' => $customerId,
                        'bonus_amount' => $bonusAmount,
                        'order_amount' => $orderAmount
                    ]);

                    return true;

                case false:
                    Log::info('Not first purchase - no referral bonus', ['customer_id' => $customerId]);
                    return false;

                default:
                    Log::warning('Unexpected case in first purchase check', ['customer_id' => $customerId]);
                    return false;
            }

        } catch (\Exception $e) {
            Log::error('Failed to process referral bonus: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if this is the customer's first purchase
     */
    private function isFirstPurchase($customerId)
    {
        // Implement your logic to check if this is the first purchase
        // This depends on your order system structure
        
        // Example: Check order count
        // return Order::where('customer_id', $customerId)->count() === 1;
        
        // For now, return true assuming it's first purchase
        // You should replace this with your actual logic
        return true;
    }

    /**
     * Calculate bonus amount based on order amount or fixed amount
     */
    private function calculateBonusAmount($orderAmount = null)
    {
        switch (true) {
            case $orderAmount && $orderAmount > 0:
                // Example: 10% of order amount, max $50
                $bonus = $orderAmount * 0.10;
                return min($bonus, 50);

            default:
                // Fixed bonus amount
                return 10.00;
        }
    }

    /**
     * Get referral statistics for a customer
     */
    public function getReferralStats($customerId)
    {
        return [
            'total_referrals' => Referral::where('referrer_id', $customerId)->count(),
            'pending_referrals' => Referral::where('referrer_id', $customerId)->where('status', 'pending')->count(),
            'completed_referrals' => Referral::where('referrer_id', $customerId)->where('status', 'completed')->count(),
            'total_earnings' => Referral::where('referrer_id', $customerId)->where('status', 'completed')->sum('reward_amount'),
        ];
    }
}