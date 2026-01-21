<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ExchangeRate;

class CurrencyController extends Controller
{
    public function switchCurrency(Request $request)
    {
        $currency = $request->input('currency');
        $allowedCurrencies = ['USD', 'EUR', 'GBP', 'INR', 'AED', 'SAR'];
        
        if (in_array($currency, $allowedCurrencies)) {
            session(['currency' => $currency]);
            $exchangeRates = self::getExchangeRates();
            
            return response()->json([
                'success' => true,
                'currency' => $currency,
                'rates' => $exchangeRates,
                'currency_data' => self::getCurrencyData()
            ]);
        }
        
        return response()->json(['success' => false], 400);
    }

    public static function getExchangeRates()
    {
        return Cache::remember('exchange_rates', 3600, function () {
            $rates = ExchangeRate::whereIn('currency', ['USD', 'EUR', 'GBP', 'INR', 'AED', 'SAR'])
                                ->pluck('rate', 'currency')
                                ->toArray();
            
            // Ensure all currencies have rates
            $defaultRates = [
                'USD' => 1,
                'EUR' => 0.92,
                'GBP' => 0.79,
                'INR' => 83.12,
                'AED' => 3.67,
                'SAR' => 3.75
            ];
            
            foreach ($defaultRates as $currency => $defaultRate) {
                if (!isset($rates[$currency])) {
                    $rates[$currency] = $defaultRate;
                }
            }
            
            return $rates;
        });
    }

    public static function getCurrencyData()
    {
        return [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'AED' => ['name' => 'UAE Dirham', 'symbol' => 'AED'],
            'SAR' => ['name' => 'Saudi Riyal', 'symbol' => 'SAR']
        ];
    }

    public static function getCurrentCurrency()
    {
        return session('currency', 'INR');
    }

    public static function getCurrencySymbol($currency = null)
    {
        $currency = $currency ?: self::getCurrentCurrency();
        $currencyData = self::getCurrencyData();
        return $currencyData[$currency]['symbol'] ?? '$';
    }

    public function getCurrentRates()
    {
        $rates = self::getExchangeRates();
        $currentCurrency = self::getCurrentCurrency();
        $currencyData = self::getCurrencyData();
        
        return response()->json([
            'success' => true,
            'current_currency' => $currentCurrency,
            'rates' => $rates,
            'currency_data' => $currencyData
        ]);
    }
}