<?php

namespace App\Helpers;

use App\Models\Coupon;

class PriceHelper
{
    /**
     * Calculate convenience fee (3% of plan amount + 18% GST on the 3%)
     */
    public static function convenienceFee(float $planAmount, ?string $currency = null): array
    {
        $currency = $currency ?? 'USD';
        
        // Load from config with proper fallbacks
        $conveniencePercentage = config('constant.convenience_percentage', 0.03);
        $gstPercentage = config('constant.gst_percentage', 0.18);

        // Ensure values are numeric and valid
        $conveniencePercentage = is_numeric($conveniencePercentage) ? floatval($conveniencePercentage) : 0.03;
        $gstPercentage = is_numeric($gstPercentage) ? floatval($gstPercentage) : 0.18;

        // Convert decimals to percentage text (0.03 → 3, 0.18 → 18)
        $convPercentText = round($conveniencePercentage * 100, 0);
        $gstPercentText = round($gstPercentage * 100, 0);

        // Calculate fees
        $convenienceFee = $planAmount * $conveniencePercentage;
        $gstOnConvenience = $convenienceFee * $gstPercentage;

        $totalConvenienceFee = $convenienceFee + $gstOnConvenience;
        $finalAmount = $planAmount + $totalConvenienceFee;

        // Ensure description is always generated
        $description = "{$convPercentText}% + {$gstPercentText}% GST";

        return [
            'plan_amount' => $planAmount,
            'convenience_fee' => $convenienceFee,
            'gst_on_convenience' => $gstOnConvenience,
            'total_convenience_fee' => $totalConvenienceFee,
            'final_amount' => $finalAmount,
            'description' => $description,
            'currency' => $currency,
            'breakdown' => [
                'convenience_percentage' => $conveniencePercentage,
                'gst_percentage' => $gstPercentage,
                'convenience_percentage_text' => $convPercentText,
                'gst_percentage_text' => $gstPercentText,
            ]
        ];
    }

    /**
     * Calculate final amount with discounts
     */
    public static function calculateFinalAmount(
        float $planAmount,
        float $miravaultUsed = 0,
        ?string $couponCode = null,
        ?int $bundleId = null,
        ?string $currency = null
    ): array {
        $currency = $currency ?? 'USD';

        // Calculate base convenience fee
        $basePrice = self::convenienceFee($planAmount, $currency);

        // Apply coupon discount if provided
        $couponDiscount = 0;
        $appliedCoupon = null;

        if ($couponCode) {
            $couponData = self::applyCoupon($planAmount, $couponCode, $bundleId);
            $couponDiscount = $couponData['discount'];
            $appliedCoupon = $couponData['coupon'];

            // Recalculate convenience fee on discounted amount
            if ($couponDiscount > 0) {
                $discountedPlanAmount = max(0, $planAmount - $couponDiscount);
                $basePrice = self::convenienceFee($discountedPlanAmount, $currency);
            }
        }

        // Apply MiraVault discount
        $finalAmount = max(0, $basePrice['final_amount'] - $miravaultUsed);

        // Ensure description is preserved when merging arrays
        return array_merge($basePrice, [
            'coupon_discount' => $couponDiscount,
            'applied_coupon' => $appliedCoupon,
            'miravault_used' => $miravaultUsed,
            'final_amount_after_discounts' => $finalAmount,
            'currency' => $currency,
            'description' => $basePrice['description'], // Explicitly preserve description
        ]);
    }

    /**
     * Apply coupon code validation and calculation
     */
    public static function applyCoupon(float $planAmount, string $couponCode, ?int $bundleId = null): array
    {
        $coupon = Coupon::where('code', strtoupper($couponCode))->first();

        if (!$coupon) {
            return ['discount' => 0, 'coupon' => null, 'error' => 'Invalid coupon code'];
        }

        if (!$coupon->isValid()) {
            return ['discount' => 0, 'coupon' => null, 'error' => 'Coupon is not valid'];
        }

        $discount = $coupon->calculateDiscount($planAmount, $bundleId);

        return [
            'discount' => $discount,
            'coupon' => $coupon,
            'error' => null
        ];
    }

    /**
     * Get currency symbol
     */
    public static function getCurrencySymbol(?string $currency = null): string
    {
        $currency = $currency ?? 'USD';

        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹',
            'AED' => 'AED ',
            'SAR' => 'SAR ',
        ];

        return $symbols[$currency] ?? $currency . ' ';
    }

    /**
     * Convert amount to different currency for payment gateways
     */
    public static function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $dbRates = \App\Models\ExchangeRate::whereIn('currency', ['USD', 'EUR', 'GBP', 'INR', 'AED', 'SAR'])
            ->pluck('rate', 'currency')
            ->toArray();

        $defaultRates = config('services.exchange_rates', [
            'USD' => 1,
            'EUR' => 0.85,
            'GBP' => 0.73,
            'INR' => 74.5,
            'AED' => 3.67,
            'SAR' => 3.75,
        ]);

        $exchangeRates = !empty($dbRates) ? $dbRates : $defaultRates;

        $fromRate = $exchangeRates[$fromCurrency] ?? 1;
        $toRate   = $exchangeRates[$toCurrency] ?? 1;

        $amountInUSD = $amount / $fromRate;
        return $amountInUSD * $toRate;
    }

    /**
     * Get default price data structure to prevent undefined array key errors
     */
    public static function getDefaultPriceData(float $planAmount, string $currency = 'USD'): array
    {
        $conveniencePercentage = 0.03;
        $gstPercentage = 0.18;
        
        $convenienceFee = $planAmount * $conveniencePercentage;
        $gstOnConvenience = $convenienceFee * $gstPercentage;
        $totalConvenienceFee = $convenienceFee + $gstOnConvenience;
        $finalAmount = $planAmount + $totalConvenienceFee;

        return [
            'plan_amount' => $planAmount,
            'convenience_fee' => $convenienceFee,
            'gst_on_convenience' => $gstOnConvenience,
            'total_convenience_fee' => $totalConvenienceFee,
            'final_amount' => $finalAmount,
            'description' => "3% + 18% GST",
            'currency' => $currency,
            'coupon_discount' => 0,
            'applied_coupon' => null,
            'miravault_used' => 0,
            'final_amount_after_discounts' => $finalAmount,
            'breakdown' => [
                'convenience_percentage' => $conveniencePercentage,
                'gst_percentage' => $gstPercentage,
                'convenience_percentage_text' => 3,
                'gst_percentage_text' => 18,
            ]
        ];
    }
}