<?php

if (!function_exists('getCurrencyData')) {
    function getCurrencyData()
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
}
if (!function_exists('getCurrencyData')) {
    function getCurrencyData()
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
}

if (!function_exists('getCurrentCurrency')) {
    function getCurrentCurrency()
    {
        return session('currency', 'USD');
    }
}

if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol($currency = null)
    {
        $currency = $currency ?: getCurrentCurrency();
        $currencyData = getCurrencyData();
        return $currencyData[$currency]['symbol'] ?? '$';
    }
}

if (!function_exists('getExchangeRates')) {
    function getExchangeRates()
    {
        return \App\Models\ExchangeRate::whereIn('currency', ['USD', 'EUR', 'GBP', 'INR', 'AED', 'SAR'])
            ->pluck('rate', 'currency')
            ->toArray();
    }
}

if (!function_exists('getCurrentCurrency')) {
    function getCurrentCurrency()
    {
        return session('currency', 'USD');
    }
}

if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol($currency = null)
    {
        $currency = $currency ?: getCurrentCurrency();
        $currencyData = getCurrencyData();
        return $currencyData[$currency]['symbol'] ?? '$';
    }
}
